<?php

declare(strict_types=1);

namespace Avenyra\GraphQlLogger\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Configuration helper for GraphQL Logger module
 *
 * Provides access to module configuration settings stored in core_config_data.
 */
class Config
{
    private const XML_PATH_LOGGER_ENABLED = 'av_graphql_logger/general/enabled';
    private const XML_PATH_CACHED_REQUEST_LOGGER_ENABLED = 'av_graphql_logger/general/cached_request_logger';

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Get store configuration value
     *
     * @param string $path Configuration path
     * @param string $scope Configuration scope (store, website, default)
     * @param int|null $storeId Store ID
     * @return mixed
     */
    public function getConfigValue(string $path, string $scope, ?int $storeId = null): mixed
    {
        return $this->scopeConfig->getValue($path, $scope, $storeId);
    }

    /**
     * Check if GraphQL logger is enabled
     *
     * @param int|null $storeId Store ID
     * @return bool
     */
    public function isLoggerEnabled(?int $storeId = null): bool
    {
        return (bool) $this->getConfigValue(
            self::XML_PATH_LOGGER_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if GraphQl logger is enabled for cached requests
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isCachedRequestLoggerEnabled(?int $storeId = null): bool
    {
        return (bool) $this->getConfigValue(
            self::XML_PATH_CACHED_REQUEST_LOGGER_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
