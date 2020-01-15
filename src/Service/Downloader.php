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
     * @return false|string downloaded file content
     */
    public function download(string $dataset, string $format, ?string $filter = null, bool $updateExisting = false)
    {
        $result = false;
        $parameters = [
            'dataset' => $dataset,
            'format' => $format
        ];
        if (isset($filter)) {
            $parameters['q'] = $filter;
        }
        $this->client->setTimeout(0);
        $content = $this->client->get('download', $parameters);
        $save = $this->finder->save($dataset, $content, $format, $filter, $updateExisting);
        if ($save) {
            $result = $this->finder->getContent($save);
        }

        return $result;
    }

    /**
     * @return false|string
     */
    public function findDownload(string $dataset, string  $format, ?string $filter = null)
    {
        return $this->finder->findDataset($dataset, $format, $filter);
    }
}
