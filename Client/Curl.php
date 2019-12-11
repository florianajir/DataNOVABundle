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

    /**
     * @param string $server
     * @param string $apiVersion
     */
    public function __construct($server, $apiVersion)
    {
        $this->server = $server;
        $this->version = $apiVersion;
    }

    /**
     * {@inheritdoc}
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * {@inheritdoc}
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function get($operation, $parameters = array(), $data = 'records')
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

    /**
     * @param string $message
     * @param array $context
     */
    private function debug($message, $context = array())
    {
        if ($this->logger) {
            $this->logger->debug($message, $context);
        }
    }

    /**
     * @param string $time
     *
     * @return array
     */
    private function logTransferTime($time)
    {
        if ($this->logger) {
            $this->logger->debug(sprintf('Transfer time: %.3f sec', $time));
        }
    }

    /**
     * @param int $status
     * @param string $response
     * @param array $parameters
     */
    private function logResponseError($status, $response, $parameters)
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

    /**
     * @param string $message
     * @param array $context
     */
    private function error($message, $context = array())
    {
        if ($this->logger) {
            $this->logger->error($message, $context);
        }
    }
}
