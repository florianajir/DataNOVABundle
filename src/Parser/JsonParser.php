<?php

namespace Fmaj\LaposteDatanovaBundle\Parser;

use Fmaj\LaposteDatanovaBundle\Service\Finder;

class JsonParser implements ParserInterface
{
    /** File format */
    const FORMAT = 'json';

    /**
     * @var Finder
     */
    private $finder;

    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @return false|array
     */
    public function parse(string $dataset)
    {
        $data = false;
        $path = $this->finder->findDataset($dataset, self::FORMAT);
        if (false !== $path) {
            $content = $this->finder->getContent($path);
            if (null !== $content) {
                $data = $this->getFieldsFromContent($content);
            }
        }

        return $data;
    }

    private function getFieldsFromContent(string $content): array
    {
        $data = array();
        $content = json_decode($content, true);
        foreach ($content as $record) {
            $data[] = $record['fields'];
        }

        return $data;
    }
}
