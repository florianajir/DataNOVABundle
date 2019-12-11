<?php

namespace Fmaj\LaposteDatanovaBundle\Client;

use Psr\Log\LoggerInterface;

/**
 * DataNOVA API Client Interface
 * Example of possible implementations : Curl, Guzzle, ...
 *
 * @author Florian Ajir <florianajir@gmail.com>
 */
interface ClientInterface
{
    /**
     * @param string $operation
     * @param array $parameters
     * @param string $data can be datasets|dataset|records(default)
     *
     * @return string
     *
     * @throws \Exception
     */
    public function get($operation, $parameters, $data = 'records');

    /**
     * Set the logger
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger);

    /**
     * Client requests timeout in seconds
     *
     * @param float $timeout
     */
    public function setTimeout($timeout);
}
