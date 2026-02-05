<?php

/**
 * Copyright © Avenyra. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Avenyra\GraphQlLogger\Logger;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger as MonologLogger;

class Handler extends Base
{
    /**
     * @var string
     */
    protected $fileName = 'var/log/graphql_queries.log';

    /**
     * @var int
     */
    protected $loggerType = MonologLogger::DEBUG;
}
