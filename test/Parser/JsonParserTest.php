<?php

namespace Fmaj\LaposteDatanovaBundle\Tests\Model;

use Fmaj\LaposteDatanovaBundle\Parser\JsonParser;
use Fmaj\LaposteDatanovaBundle\Service\Finder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class JsonParserTest extends TestCase
{
    public function testParseJsonFixture()
    {
        $dataset = 'laposte_hexasmal';
        $path = __DIR__ . '/Fixtures/laposte_hexasmal.json';
        $finder = $this->getFinderMock($dataset, $path);

        $jsonParser = new JsonParser($finder);
        $result = $jsonParser->parse($dataset);

        $this->assertNotFalse($result);
        $this->assertCount(15, $result);
        $this->assertArrayHasKey('code_commune_insee', $result[0]);
        $this->assertArrayHasKey('nom_de_la_commune', $result[0]);
        $this->assertArrayHasKey('code_postal', $result[0]);
        $this->assertArrayHasKey('libell_d_acheminement', $result[0]);
        $this->assertEquals('57077', $result[0]['code_commune_insee']);
        $this->assertEquals('BEZANGE LA PETITE', $result[0]['nom_de_la_commune']);
        $this->assertEquals('57630', $result[0]['code_postal']);
        $this->assertEquals('BEZANGE LA PETITE', $result[0]['libell_d_acheminement']);
    }

    /**
     * @return MockObject|Finder
     */
    private function getFinderMock(string $dataset, string $path)
    {
        $finder = $this->getMockBuilder(Finder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $finder->expects($this->once())
            ->method('findDataset')
            ->with($dataset, JsonParser::FORMAT)
            ->willReturn($path);
        $finder->expects($this->once())
            ->method('getContent')
            ->with($path)
            ->willReturn(file_get_contents($path));

        return $finder;
    }
}
