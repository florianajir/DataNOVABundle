<?php

namespace Fmaj\LaposteDatanovaBundle\Manager;

use Fmaj\LaposteDatanovaBundle\Model\Download;
use Fmaj\LaposteDatanovaBundle\Model\Search;
use Fmaj\LaposteDatanovaBundle\Client\ClientInterface;
use Fmaj\LaposteDatanovaBundle\Service\Downloader;
use Fmaj\LaposteDatanovaBundle\Service\Finder;
use Fmaj\LaposteDatanovaBundle\Parser\ParserInterface;
use Psr\Log\LoggerInterface;

class RecordsManager
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var Downloader
     */
    private $downloader;

    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var ParserInterface[]
     */
    private $parsers;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param ClientInterface $client
     * @param Downloader $downloader
     * @param Finder $finder
     */
    public function __construct(ClientInterface $client, Downloader $downloader, Finder $finder)
    {
        $this->parsers = array();
        $this->client = $client;
        $this->downloader = $downloader;
        $this->finder = $finder;
    }

    /**
     * {@inheritdoc}
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param ParserInterface $parser
     */
    public function addParser(ParserInterface $parser)
    {
        $this->parsers[] = $parser;
    }

    /**
     * @param Search $search
     *
     * @return array|false
     */
    public function search(Search $search)
    {
        //look locally (parser)
        $data = $this->searchLocally($search);
        //query remote api
        if (false === $data) {
            $data = $this->searchDistant($search);
        }

        return $data;
    }

    /**
     * Look locally (parsers)
     *
     * @param Search $search
     *
     * @return array|false
     */
    private function searchLocally(Search $search)
    {
        $this->log('debug', sprintf('Search locally for %s dataset', $search->getDataset()), $search->getParameters());
        $result = false;
        foreach ($this->parsers as $parser) {
            $parsed = $parser->parse($search->getDataset());
            if ($parsed) {
                $result = $this->searchInArrayData($parsed, $search);
                $this->sortLocalData($result, $search);
                $this->log(
                    'debug',
                    sprintf(
                        'Local dataset %s found (%s)',
                        $search->getDataset(),
                        get_class($parser)
                    ),
                    $search->getParameters()
                );
                break;
            }
        }

        return $result;
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     */
    private function log($level, $message, $context = array())
    {
        if ($this->logger) {
            $this->logger->log($level, $message, $context);
        }
    }

    /**
     * @param array $parsed
     * @param Search $search
     *
     * @return array
     */
    private function searchInArrayData(array $parsed, Search $search)
    {
        $data = array();
        foreach ($parsed as $index => $line) {
            if ($search->getStart() > $index) {
                continue;
            }
            if ($this->matchToSearch($search, $line)) {
                $data[] = $line;
            }
            if ($search->getRows() == count($data)) {
                break;
            }
        }

        return $data;
    }

    /**
     * @param Search $search
     * @param array $line
     *
     * @return bool
     */
    private function matchToSearch(Search $search, $line)
    {
        $match = false;
        if (null !== $search->getFilter()) {
            $column = $search->getFilterColumn();
            $value = $search->getFilterValue();
            if (!empty($column)) {
                if (array_key_exists($column, $line) && $value == $line[$column]) {
                    $match = true;
                }
            } else {
                foreach ($line as $data) {
                    if ($this->isLike($data, $value)) {
                        $match = true;
                        break;
                    }
                }
            }
        } else {
            $match = true;
        }

        return $match;
    }

    /**
     * @param string $data
     * @param string $term
     * @param bool $sensitive
     *
     * @return bool
     */
    private function isLike($data, $term, $sensitive = false)
    {
        $match = false;
        if (false === $sensitive) {
            $term = strtolower($term);
            $data = strtolower($data);
        }
        if (false !== strpos($data, $term)) {
            $match = true;
        }

        return $match;
    }

    /**
     * @param array $parsed
     * @param Search $search
     *
     * @return array
     */
    private function sortLocalData(array &$parsed, Search $search)
    {
        $sortKey = $search->getSort();
        if (!empty($sortKey)) {
            $sorter = function ($key) {
                return function ($elt1, $elt2) use ($key) {
                    return strnatcmp($elt1[$key], $elt2[$key]);
                };
            };
            usort($parsed, $sorter($sortKey));
        }

        return $parsed;
    }

    /**
     * Look locally (parsers)
     *
     * @param Search $search
     *
     * @return array|false
     */
    private function searchDistant(Search $search)
    {
        $data = array();
        $result = $this->client->get('search', $search->getParameters());
        if ($result) {
            $result = json_decode($result, true);
            foreach ($result['records'] as $record) {
                $data[] = $record['fields'];
            }
        }

        return $data;
    }

    /**
     * @param Download $download
     * @param bool $forceUpdate
     *
     * @return false|string
     */
    public function download(Download $download, $forceUpdate = false)
    {
        return $this->downloader->download(
            $download->getDataset(),
            $download->getFormat(),
            $download->getFilter(),
            $forceUpdate
        );
    }

    /**
     * @param Download $download
     *
     * @return null|string
     */
    public function getLocalDatasetContent(Download $download)
    {
        $content = null;
        $filepath = $this->finder->findDataset(
            $download->getDataset(),
            $download->getFormat(),
            $download->getFilter()
        );
        if (false !== $filepath) {
            $content = $this->finder->getContent($filepath);
        }

        return $content;
    }
}
