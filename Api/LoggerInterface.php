<?php

namespace Avenyra\GraphQlLogger\Api;

use Magento\GraphQl\Model\Query\Logger\LoggerInterface as GraphQlLoggerInterface;

interface LoggerInterface extends GraphQlLoggerInterface
{
    public const REQUEST_URL = 'GraphQlRequestUrl';
    public const QUERY = 'GraphQlQuery';
    public const VARIABLES = 'GraphQlVariables';
}
