<?php

namespace Fmaj\LaposteDatanovaBundle\Tests\Model;

use Fmaj\LaposteDatanovaBundle\Parser\CsvParser;

class CsvParserTest extends \PHPUnit_Framework_TestCase
{
    public function testParseCsvFixture()
    {
        $dataset = 'laposte_hexasmal';
        $finder = $this->getFinderMock($dataset, dirname(__FILE__) . '/Fixtures/laposte_hexasmal.csv');

        $csvParser = new CsvParser($finder);
        $result = $csvParser->parse($dataset);

        $this->assertNotFalse($result);
        $this->assertCount(15, $result);
        $this->assertArrayHasKey('code_commune_insee', $result[0]);
        $this->assertArrayHasKey('nom_commune', $result[0]);
        $this->assertArrayHasKey('code_postal', $result[0]);
        $this->assertArrayHasKey('libelle_acheminement', $result[0]);
        $this->assertArrayHasKey('ligne_5', $result[0]);
        $this->assertEquals('57077', $result[0]['code_commune_insee']);
        $this->assertEquals('BEZANGE LA PETITE', $result[0]['nom_commune']);
        $this->assertEquals('57630', $result[0]['code_postal']);
        $this->assertEquals('BEZANGE LA PETITE', $result[0]['libelle_acheminement']);
        $this->assertEquals('', $result[0]['ligne_5']);
    }

    /**
     * @param string $dataset
     * @param string $path
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Fmaj\LaposteDatanovaBundle\Service\Finder
     */
    private function getFinderMock($dataset, $path)
    {
        $mock = $this->getMockBuilder('Fmaj\LaposteDatanovaBundle\Service\Finder')
            ->disableOriginalConstructor()
            ->getMock();
        $mock->expects($this->once())
            ->method('findDataset')
            ->with($dataset, CsvParser::FORMAT)
            ->willReturn($path);

        return $mock;
    }
}
