<?php

namespace Fmaj\LaposteDatanovaBundle\Model;

class Download extends Parameters
{
    /**
     * @param string $dataset (mandatory) datasetid
     * @param string $format
     */
    public function __construct($dataset, $format)
    {
        $parameters = array();
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
