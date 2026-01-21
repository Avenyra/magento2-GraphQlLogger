<?php

namespace Avenyra\GraphQlLogger\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const XML_PATH_LOGGER_ENABLED = 'av_graphql_logger/general/enabled';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Get store config value
     *
     * @param string $path
     * @param string $scope
     * @param int|null $storeId
     * @return mixed
     */
    public function getConfigValue(string $path, string $scope, int $storeId = null): mixed
    {
        return $this->scopeConfig->getValue($path, $scope, $storeId);
    }

    /**
     * @param int|null $storeId
     * @return bool
     */
    public function isLoggerEnabled(int $storeId = null): bool
    {
        return (bool) $this->getConfigValue(self::XML_PATH_LOGGER_ENABLED, ScopeInterface::SCOPE_STORE, $storeId);
    }
}
