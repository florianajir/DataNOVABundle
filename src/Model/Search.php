<?php

namespace Fmaj\LaposteDatanovaBundle\Model;

class Search extends Parameters
{
    public function __construct(string $dataset)
    {
        $parameters = array();
        $parameters['dataset'] = $dataset;
        parent::__construct($parameters);
    }

    public function getDataset(): string
    {
        return $this->get('dataset');
    }

    public function setStart(int $start): self
    {
        $this->set('start', $start);

        return $this;
    }

    public function getStart(): int
    {
        return $this->get('start', 0);
    }

    public function setRows(int $rows): self
    {
        $this->set('rows', $rows);

        return $this;
    }

    public function getRows(): int
    {
        return $this->get('rows', 10);
    }

    public function setSort(string $sort): self
    {
        $this->set('sort', $sort);

        return $this;
    }

    public function getSort(): ?string
    {
        return $this->get('sort');
    }
}
