<?php

namespace Fmaj\LaposteDatanovaBundle\Service;

use Fmaj\LaposteDatanovaBundle\Client\ClientInterface;

class Downloader
{
    /** @var ClientInterface $client */
    private $client;

    /** @var  Finder $finder */
    private $finder;

    /**
     * @param ClientInterface $client
     * @param Finder $finder
     */
    public function __construct(ClientInterface $client, Finder $finder)
    {
        $this->client = $client;
        $this->finder = $finder;
    }

    /**
     * @param string $dataset
     * @param string $format
     * @param string $filter
     * @param bool $updateExisting
     *
     * @return false|string downloaded file content
     */
    public function download($dataset, $format, $filter = null, $updateExisting = false)
    {
        $result = false;
        $parameters = array(
            'dataset' => $dataset,
            'format' => $format
        );
        if (isset($filter)) {
            $parameters['q'] = $filter;
        }
        $this->client->setTimeout(0);
        $content = $this->client->get('download', $parameters);
        $save = $this->finder->save($dataset, $content, $format, $filter, $updateExisting);
        if (false !== $save) {
            $result = $this->finder->getContent($save);
        }

        return $result;
    }

    /**
     * @param string $dataset
     * @param string $format
     * @param string $filter
     * @return false|string
     */
    public function findDownload($dataset, $format, $filter = null)
    {
        return $this->finder->findDataset($dataset, $format, $filter);
    }
}
