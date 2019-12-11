<?php

namespace Fmaj\LaposteDatanovaBundle\Tests\Model;

use Fmaj\LaposteDatanovaBundle\Model\Download;

class DownloadTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $dataset = uniqid();
        $format = uniqid();
        $download = new Download($dataset, $format);
        $this->assertEquals($dataset, $download->getDataset());
        $this->assertEquals($format, $download->getFormat());
        $this->assertCount(2, $download->getParameters());
    }
}
