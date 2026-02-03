<?php

namespace Avenyra\GraphQlLogger\Helper;

use Avenyra\GraphQlLogger\Logger\Logger as GraphQlLogger;
use Avenyra\GraphQlLogger\Api\LoggerInterface;

class Logger
{
    /**
     * @param GraphQlLogger $logger
     */
    public function __construct(
        private readonly GraphQlLogger $logger
    ) {
    }

    /**
     * @param $queryDetails
     * @return void
     */
    public function log($queryDetails): void
    {
        $formattedData = $this->formatQueryDetails($queryDetails);
        $this->logger->debug($formattedData);
    }

    /**
     * Format query details into a readable string
     *
     * @param array $queryDetails
     * @return string
     */
    private function formatQueryDetails(array $queryDetails): string
    {
        $lines = [];
        $lines[] = '';
        $lines[] = sprintf(
            'Top Level Operation Name : %s',
            $queryDetails[LoggerInterface::TOP_LEVEL_OPERATION_NAME] ?? 'N/A'
        );
        $lines[] = sprintf(
            'Operation Names : %s',
            $queryDetails[LoggerInterface::OPERATION_NAMES] ?? 'N/A'
        );
        $lines[] = sprintf(
            'Store : %s',
            $queryDetails[LoggerInterface::STORE_HEADER] ?? 'N/A'
        );
        $lines[] = sprintf(
            'Method : %s',
            $queryDetails[LoggerInterface::HTTP_METHOD] ?? 'N/A'
        );
        $lines[] = sprintf(
            'URL : %s',
            $queryDetails[LoggerInterface::REQUEST_URL] ?? 'N/A'
        );
        $lines[] = 'Query :';
        $lines[] = $queryDetails[LoggerInterface::QUERY] ?? 'N/A';
        $lines[] = '';
        $lines[] = 'Variables :';

        if (!empty($queryDetails[LoggerInterface::VARIABLES])) {
            $lines[] = $queryDetails[LoggerInterface::VARIABLES];
        } else {
            $lines[] = '{}';
        }

        return implode(PHP_EOL, $lines);
    }
}
