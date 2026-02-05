<?php

/**
 * Copyright © Avenyra. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Avenyra\GraphQlLogger\Model\Query\Logger;

use Avenyra\GraphQlLogger\Helper\Config as LoggerConfig;
use Avenyra\GraphQlLogger\Helper\Logger;
use Avenyra\GraphQlLogger\Api\LoggerInterface;

class GraphQlLogger implements LoggerInterface
{
    /**
     * @param LoggerConfig $loggerConfig
     * @param Logger $logger
     */
    public function __construct(
        private readonly LoggerConfig $loggerConfig,
        private readonly Logger $logger
    ) {
    }

    /**
     * Execute logger to record GraphQL query details
     *
     * @param array $queryDetails
     * @return void
     */
    public function execute(array $queryDetails): void
    {
        if (!$this->loggerConfig->isLoggerEnabled() || $queryDetails[LoggerInterface::HTTP_METHOD] === 'GET') {
            return;
        }
        $this->logger->log($queryDetails);
    }

    /**
     * Execute logger to record cached GraphQL query details
     *
     * @param array $queryDetails
     * @return void
     */
    public function logCachedQuery(array $queryDetails): void
    {
        if (!$this->loggerConfig->isLoggerEnabled() || !$this->loggerConfig->isCachedRequestLoggerEnabled()) {
            return;
        }
        $this->logger->log($queryDetails);
    }
}
