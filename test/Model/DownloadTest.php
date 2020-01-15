<?php

namespace Fmaj\LaposteDatanovaBundle\Tests\Model;

use Fmaj\LaposteDatanovaBundle\Model\Download;
use PHPUnit\Framework\TestCase;

class DownloadTest extends TestCase
{
    public function testConstruct()
    {
        $dataset = uniqid('', true);
        $format = uniqid('', true);
        $download = new Download($dataset, $format);
        $this->assertEquals($dataset, $download->getDataset());
        $this->assertEquals($format, $download->getFormat());
        $this->assertCount(2, $download->getParameters());
    }
}
