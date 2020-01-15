<?php

namespace Fmaj\LaposteDatanovaBundle\Model;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @author Florian Ajir <florianajir@gmail.com>
 */
abstract class Parameters extends ParameterBag
{
    /**
     * {@inheritdoc}
     */
    public function __construct(array $parameters = array())
    {
        parent::__construct($parameters);
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getFilter(): string
    {
        return $this->get('q');
    }

    public function setFilter(string $query): self
    {
        $this->set('q', $query);

        return $this;
    }

    public function setLang(string $lang): self
    {
        $this->set('lang', $lang);

        return $this;
    }

    public function getLang(): ?string
    {
        return $this->get('lang');
    }

    /**
     * Get column prefix of a query
     */
    public function getFilterColumn(): string
    {
        $column = null;
        $filterAssoc = $this->explodeFilter();
        if (isset($filterAssoc[0])) {
            $column = $filterAssoc[0];
        }

        return $column;
    }

    protected function explodeFilter(): array
    {
        $explode = array();
        $query = $this->get('q');
        if (null !== $query) {
            if (false !== strpos($query, ':')) {
                $explode = explode(':', $query);
            } elseif (false !== strpos($query, '=')) {
                $explode = explode('=', $query);
            }
        }

        return $explode;
    }

    public function getFilterValue(): string
    {
        $value = $this->get('q');
        $filterAssoc = $this->explodeFilter();
        if (isset($filterAssoc[1])) {
            $value = $filterAssoc[1];
        }

        return $value;
    }
}
