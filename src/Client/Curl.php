<?php

namespace Fmaj\LaposteDatanovaBundle\Client;

use Exception;
use Psr\Log\LoggerInterface;

class Curl implements ClientInterface
{
    /** @var string */
    protected $server;

    /** @var string */
    protected $version;

    /** @var LoggerInterface */
    protected $logger;

    /** @var float */
    protected $timeout;

    public function __construct(string $server, string $apiVersion)
    {
        $this->server = $server;
        $this->version = $apiVersion;
    }

    public function setTimeout(float $timeout): void
    {
        $this->timeout = $timeout;
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function get(string $operation, array $parameters, string $data = 'records'): string
    {
        $this->debug(sprintf('%s %s', $operation, $data), $parameters);
        $result = null;
        $url = sprintf(
            '%s/api/%s/%s/%s/?%s',
            $this->server,
            $data,
            $this->version,
            $operation,
            http_build_query($parameters)
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
        ));
        if (isset($this->timeout)) {
            curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        }
        try {
            $response = curl_exec($curl);
            if (!$response) {
                $this->error(
                    'Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl) . ' - Url: ' . $url,
                    $parameters
                );
            } else {
                $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                $time = curl_getinfo($curl, CURLINFO_TOTAL_TIME);
                $this->logTransferTime($time);
                if (200 === $status) {
                    $result = $response;
                } else {
                    $this->debug('Target url:  ' . $url);
                    $this->logResponseError($status, $response, $parameters);
                }
            }
            curl_close($curl);
        } catch (Exception $exception) {
            $this->debug($exception->getTraceAsString());
            $this->error($exception->getMessage());
        }

        return $result;
    }

    private function debug(string $message, array $context = []): void
    {
        if ($this->logger) {
            $this->logger->debug($message, $context);
        }
    }

    private function logTransferTime(string $time): void
    {
        if ($this->logger) {
            $this->logger->debug(sprintf('Transfer time: %.3f sec', $time));
        }
    }

    private function logResponseError(int $status, string $response, array $parameters): void
    {
        if ($this->logger) {
            $log = sprintf(
                '%d: %s',
                $status,
                $response
            );
            $this->logger->error($log, $parameters);
        }
    }

    private function error(string $message, array $context = []): void
    {
        if ($this->logger) {
            $this->logger->error($message, $context);
        }
    }
}
