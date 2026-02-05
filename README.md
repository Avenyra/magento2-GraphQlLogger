# Avenyra GraphQl Logger

Avenyra GraphQL Logger is a utility module for Magento 2 that logs incoming GraphQL requests. It is built for debugging headless storefronts, mobile apps, and third-party integrations without turning your logs into a liability.

## Features

- **Logs GraphQL request metadata**
    - Query
    - Variables
    - Request URL

- **Configurable redaction of sensitive data**
    - Variable keys
    - Query literal values

- **Optional cached GET logging** (disabled by default)
- **Authorization-based filtering**
    - Log only requests whose `Authorization` header matches configured tokens
    - Optional **force log** to capture those requests even when global logging is disabled

- **Lightweight and non-invasive**
    - Hooks into Magento’s GraphQL logger pool
    - Writes to a dedicated log file under `var/log`

## Requirements

- PHP 8.1+
- Magento 2.4.5+

## Installation

### Via Composer (Recommended)

```bash
composer require avenyra/module-graph-ql-logger
php bin/magento module:enable Avenyra_GraphQlLogger
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
php bin/magento cache:flush
```

### Log location

Logs are written to `var/log/graphql_queries.log` by default (relative to Magento BP).

### Security and privacy

The module attempts to minimize sensitive data in logs:

- Variables array is sanitized using the configurable redaction list (redacted values are replaced with `***REDACTED***`).

### Performance

- Logging all GraphQL requests can generate heavy I/O on busy sites. Keep cached logging off unless needed.

### Notes

This logger is intended as a developer and operations tool. If you enable logging in production, ensure you have a data retention and access policy that meets your compliance requirements (PCI, GDPR, etc.).

## Support

Found a bug or issue? Please <a href="https://github.com/Avenyra/magento2-GraphQlLogger/issues">open an issue</a> on GitHub.

## Author

**Avenyra Solutions**
