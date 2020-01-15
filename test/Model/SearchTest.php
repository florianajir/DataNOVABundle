<?php

namespace Fmaj\LaposteDatanovaBundle\Tests\Model;

use Fmaj\LaposteDatanovaBundle\Model\Search;
use PHPUnit\Framework\TestCase;

class SearchTest extends TestCase
{
    public function testConstruct()
    {
        $dataset = uniqid('', true);
        $search = new Search($dataset);
        $this->assertEquals($dataset, $search->getDataset());
        $this->assertCount(1, $search->getParameters());
    }

    public function testDefaults()
    {
        $dataset = uniqid('', true);
        $search = new Search($dataset);
        $this->assertEquals(0, $search->getStart());
        $this->assertEquals(10, $search->getRows());
        $this->assertNull($search->getSort());
    }

    public function testAccessors()
    {
        $dataset = uniqid('', true);
        $search = new Search($dataset);
        $search->setStart(10);
        $this->assertEquals(10, $search->getStart());
        $search->setRows(5);
        $this->assertEquals(5, $search->getRows());
        $search->setSort('test');
        $this->assertEquals('test', $search->getSort());
        $search->setLang('fr');
        $this->assertEquals('fr', $search->getLang());
    }

    public function testQueryFilter()
    {
        $this->createQueryColumnFilterSearch(uniqid('', true), uniqid('', true), ':');
        $this->createQueryColumnFilterSearch(uniqid('', true), uniqid('', true), '=');
    }

    private function createQueryColumnFilterSearch($column, $value, $delimiter = ':')
    {
        $filter = sprintf('%s%s%s', $column, $delimiter, $value);
        $dataset = uniqid('', true);
        $search = new Search($dataset);
        $search->setFilter($filter);
        $this->assertEquals($filter, $search->getFilter());
        $this->assertEquals($column, $search->getFilterColumn());
        $this->assertEquals($value, $search->getFilterValue());
    }
}
