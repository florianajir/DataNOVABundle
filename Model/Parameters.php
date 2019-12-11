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

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return string $query
     */
    public function getFilter()
    {
        return $this->get('q');
    }

    /**
     * @param string $query
     *
     * @return $this
     */
    public function setFilter($query)
    {
        $this->set('q', $query);

        return $this;
    }

    /**
     * @param $lang
     *
     * @return self
     */
    public function setLang($lang)
    {
        $this->set('lang', $lang);

        return $this;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->get('lang');
    }

    /**
     * Get column prefix of a query
     *
     * @return string
     */
    public function getFilterColumn()
    {
        $column = null;
        $filterAssoc = $this->explodeFilter();
        if (isset($filterAssoc[0])) {
            $column = $filterAssoc[0];
        }

        return $column;
    }

    /**
     * @return array
     */
    protected function explodeFilter()
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

    /**
     * @return string
     */
    public function getFilterValue()
    {
        $value = $this->get('q');
        $filterAssoc = $this->explodeFilter();
        if (isset($filterAssoc[1])) {
            $value = $filterAssoc[1];
        }

        return $value;
    }
}
