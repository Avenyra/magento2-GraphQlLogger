<?php

namespace Avenyra\GraphQlLogger\Model\Query\Logger;

use Avenyra\GraphQlLogger\Helper\Config as LoggerConfig;
use Magento\GraphQl\Model\Query\Logger\LoggerInterface;

class GraphQlLogger implements LoggerInterface
{
    /**
     * @param LoggerConfig $loggerConfig
     */
    public function __construct(
        private readonly LoggerConfig $loggerConfig
    ) {
    }

    /**
     * @param array $queryDetails
     * @return void
     * @throws \Zend_Log_Exception
     */
    public function execute(array $queryDetails): void
    {
        if (!$this->loggerConfig->isLoggerEnabled()) {
            return;
        }
        $logger = $this->getLoggerObject();
        $logger->info('GraphQl query executed: ' . print_r($queryDetails, true));
    }

    /**
     * @return \Zend_Log
     * @throws \Zend_Log_Exception
     */
    private function getLoggerObject()
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        return $logger;
    }
}
