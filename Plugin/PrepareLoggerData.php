<?php

/**
 * Copyright © Avenyra. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Avenyra\GraphQlLogger\Plugin;

use Avenyra\GraphQlLogger\Helper\Config as LoggerConfig;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\Http as HttpResponse;
use Magento\Framework\GraphQl\Schema;
use Magento\GraphQl\Helper\Query\Logger\LogData;
use Avenyra\GraphQlLogger\Api\LoggerInterface;

class PrepareLoggerData
{
    /**
     * @param LoggerConfig $loggerConfig
     */
    public function __construct(
        private readonly LoggerConfig $loggerConfig
    ) {
    }

    /**
     * Add custom data to log data after it's prepared
     *
     * @param LogData $subject
     * @param array $result
     * @param RequestInterface $request
     * @param array $data
     * @param Schema|null $schema
     * @param HttpResponse|null $response
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetLogData(
        LogData $subject,
        array $result,
        RequestInterface $request,
        array $data,
        ?Schema $schema,
        ?HttpResponse $response
    ): array {
        if (!$this->loggerConfig->isLoggerEnabled()) {
            return $result;
        }

        $result[LoggerInterface::REQUEST_URL] = $request->getUriString();
        $result[LoggerInterface::QUERY] = $data['query'] ?? '';
        $result[LoggerInterface::VARIABLES] = null;

        if (isset($data['variables']) && is_array($data['variables'])) {
            $result[LoggerInterface::VARIABLES] = json_encode($data['variables'], JSON_PRETTY_PRINT);
        }

        return $result;
    }
}
