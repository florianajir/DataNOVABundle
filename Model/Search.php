<?php

namespace Fmaj\LaposteDatanovaBundle\Model;

class Search extends Parameters
{
    /**
     * @param string $dataset (mandatory) datasetid
     */
    public function __construct($dataset)
    {
        $parameters = array();
        $parameters['dataset'] = $dataset;
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
     * @param int $start
     *
     * @return self
     */
    public function setStart($start)
    {
        $this->set('start', $start);

        return $this;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->get('start', 0);
    }

    /**
     * @param int $rows
     *
     * @return self
     */
    public function setRows($rows)
    {
        $this->set('rows', $rows);

        return $this;
    }

    /**
     * @return int
     */
    public function getRows()
    {
        return $this->get('rows', 10);
    }

    /**
     * @param string $sort
     *
     * @return self
     */
    public function setSort($sort)
    {
        $this->set('sort', $sort);

        return $this;
    }

    /**
     * @return string
     */
    public function getSort()
    {
        return $this->get('sort');
    }
}
