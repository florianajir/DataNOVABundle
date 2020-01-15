<?php

namespace Fmaj\LaposteDatanovaBundle\Parser;

use Fmaj\LaposteDatanovaBundle\Service\Finder;

class CsvParser implements ParserInterface
{
    /** File format */
    const FORMAT = 'csv';

    /** CSV delimiter */
    const DELIMITER = ';';

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
    public function parse(string $dataset, string $delimiter = self::DELIMITER)
    {
        $data = false;
        $path = $this->finder->findDataset($dataset, self::FORMAT);
        if (false !== $path) {
            $data = array();
            $lines = file($path);
            $columns = str_getcsv(strtolower($lines[0]), $delimiter);
            unset($lines[0]);
            foreach ($lines as $line) {
                $lineValues = str_getcsv($line, $delimiter);
                $lineData = array();
                foreach ($lineValues as $key => $value) {
                    $lineData[$columns[$key]] = $value;
                }
                $data[] = $lineData;
            }
        }

        return $data;
    }
}
