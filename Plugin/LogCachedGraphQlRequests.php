<?php

/**
 * Copyright © Avenyra. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Avenyra\GraphQlLogger\Plugin;

use GraphQL\Error\SyntaxError;
use Magento\Framework\App\FrontControllerInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\QueryParser;
use Magento\Framework\GraphQl\Schema\SchemaGeneratorInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\GraphQl\Helper\Query\Logger\LogData;
use Avenyra\GraphQlLogger\Model\Query\Logger\GraphQlLogger;
use Magento\Framework\GraphQl\Query\Fields as QueryFields;

class LogCachedGraphQlRequests
{
    protected $httpResponse;

    /**
     * @var QueryParser
     */
    private QueryParser $queryParser;

    /**
     * @param SerializerInterface $jsonSerializer
     * @param LogData $logDataHelper
     * @param SchemaGeneratorInterface $schemaGenerator
     * @param GraphQlLogger $graphQlLogger
     * @param QueryFields $queryFields
     * @param QueryParser|null $queryParser
     */
    public function __construct(
        private readonly SerializerInterface $jsonSerializer,
        private readonly LogData $logDataHelper,
        private readonly SchemaGeneratorInterface $schemaGenerator,
        private readonly GraphQlLogger $graphQlLogger,
        private readonly QueryFields $queryFields,
        ?QueryParser $queryParser = null
    ) {
        $this->queryParser = $queryParser ?: ObjectManager::getInstance()->get(QueryParser::class);
    }

    /**
     * Plugin to log cached GraphQL requests
     *
     * @param FrontControllerInterface $_
     * @param RequestInterface $request
     * @return array
     * @throws GraphQlInputException
     * @throws SyntaxError
     */
    public function beforeDispatch(FrontControllerInterface $_, RequestInterface $request): array
    {
        $data = $this->getDataFromRequest($request);
        $query = $data['query'] ?? '';
        $schema = null;

        if ($request->isGet()) {
            $parsedQuery = $this->queryParser->parse($query);
            $data['parsedQuery'] = $parsedQuery;

            $this->queryFields->setQuery($parsedQuery, $data['variables'] ?? null);
            $schema = $this->schemaGenerator->generate();

            if (!isset($data['query']) || !str_contains($data['query'], 'IntrospectionQuery')) {
                $queryInformation = $this->logDataHelper->getLogData($request, $data, $schema, $this->httpResponse);
                $this->graphQlLogger->logCachedQuery($queryInformation);
            }
        }
        return [$request];
    }

    /**
     * Get data from request body or query string
     *
     * @param RequestInterface $request
     * @return array
     * @throws GraphQlInputException
     */
    private function getDataFromRequest(RequestInterface $request): array
    {
        $data = [];
        try {
            /** @var Http $request */
            if ($request->isPost()) {
                $data = $request->getContent() ? $this->jsonSerializer->unserialize($request->getContent()) : [];
            } elseif ($request->isGet()) {
                $data = $request->getParams();
                $data['variables'] = !empty($data['variables']) && is_string($data['variables'])
                    ? $this->jsonSerializer->unserialize($data['variables'])
                    : null;
            }
        } catch (\InvalidArgumentException $e) {
            throw new GraphQlInputException(__('Unable to parse the request.'), $e);
        }

        return $data;
    }
}
