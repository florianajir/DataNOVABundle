<?php

namespace Fmaj\LaposteDatanovaBundle\Model;

class Download extends Parameters
{
    public function __construct(string $dataset, string $format)
    {
        $parameters = [];
        $parameters['dataset'] = $dataset;
        $parameters['format'] = $format;
        parent::__construct($parameters);
    }

    /**
     * @return string
     */
    public function getDataset()
    {
        return $this->get('dataset');
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->get('format');
    }
}
