<?php

declare(strict_types=1);

namespace Avenyra\GraphQlLogger\Logger;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Level;

/**
 * Custom log handler for GraphQL queries
 *
 * Writes GraphQL query logs to a dedicated log file.
 */
class Handler extends Base
{
    /**
     * @var string
     */
    protected $fileName = '/var/log/graphql_debug.log';

    /**
     * @var int
     */
    protected $loggerType = \Monolog\Logger::DEBUG;
}
