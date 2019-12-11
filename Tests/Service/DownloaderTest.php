<?php

namespace Fmaj\LaposteDatanovaBundle\Tests\Model;

use Fmaj\LaposteDatanovaBundle\Service\Downloader;

class DownloaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Simple find download test
     */
    public function testFindDownload()
    {
        $dataset = 'laposte_hexasmal';
        $format = 'json';
        $filter = '34000';
        $path = uniqid();
        $client = $this->getClientMock();
        $finder = $this->getFinderMock();
        $finder->expects($this->once())
            ->method('findDataset')
            ->with($dataset, $format, $filter)
            ->willReturn($path);
        $downloader = new Downloader($client, $finder);
        $result = $downloader->findDownload($dataset, $format, $filter);
        $this->assertEquals($path, $result);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Fmaj\LaposteDatanovaBundle\Client\ClientInterface
     */
    private function getClientMock()
    {
        return $this->getMockBuilder('Fmaj\LaposteDatanovaBundle\Client\ClientInterface')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Fmaj\LaposteDatanovaBundle\Service\Finder
     */
    private function getFinderMock()
    {
        return $this->getMockBuilder('Fmaj\LaposteDatanovaBundle\Service\Finder')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Simple test of download method
     */
    public function testDownload()
    {
        $dataset = 'laposte_hexasmal';
        $format = 'json';
        $filter = '34000';
        $updateExisting = false;
        $content = uniqid();
        $path = uniqid();
        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('setTimeout')
            ->with(0);
        $client->expects($this->once())
            ->method('get')
            ->with('download', array(
                'dataset' => $dataset,
                'format' => $format,
                'q' => $filter
            ))
            ->willReturn($content)
        ;
        $finder = $this->getFinderMock();
        $finder->expects($this->once())
            ->method('save')
            ->with($dataset, $content, $format, $filter, $updateExisting)
            ->willReturn($path);
        $finder->expects($this->once())
            ->method('getContent')
            ->with($path)
            ->willReturn($content);
        $downloader = new Downloader($client, $finder);
        $result = $downloader->download($dataset, $format, $filter, $updateExisting);
        $this->assertEquals($content, $result);
    }
}
