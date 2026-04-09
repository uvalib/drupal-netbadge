<?php
/**
 * SimpleSAMLphp configuration for production deployment
 * Uses environment variables for container deployment
 * From ansible/saml_config 
 */

use SimpleSAML\Logger;

$config = [
    // Basic configuration - environment-driven
    'baseurlpath' => getenv('SIMPLESAML_BASE_URL') ?: 'https://your-domain.com/simplesaml/',
    'certdir' => '/var/simplesamlphp/cert/',
    'loggingdir' => '/var/simplesamlphp/log/',
    'datadir' => '/var/simplesamlphp/data/',
    'metadatadir' => '/var/simplesamlphp/metadata/',
    'tempdir' => '/tmp/simplesamlphp/',
    'cachedir' => '/var/cache/simplesamlphp/',

    // Security settings - from environment
    'secretsalt' => getenv('SIMPLESAML_SECRET_SALT') ?: 'CHANGE_THIS_IN_PRODUCTION',
    'auth.adminpassword' => getenv('SIMPLESAML_ADMIN_PASSWORD') ?: 'CHANGE_THIS_PASSWORD',
    'admin.protectindexpage' => filter_var(getenv('SIMPLESAML_PROTECT_INDEX') ?: 'true', FILTER_VALIDATE_BOOLEAN),
    'admin.protectmetadata' => filter_var(getenv('SIMPLESAML_PROTECT_METADATA') ?: 'true', FILTER_VALIDATE_BOOLEAN),

    // Technical contact - from environment
    'technicalcontact_name' => getenv('SIMPLESAML_TECH_NAME') ?: 'System Administrator',
    'technicalcontact_email' => getenv('SIMPLESAML_TECH_EMAIL') ?: 'admin@your-domain.com',


    // Session configuration - environment-driven
    'session.cookie.name' => 'SimpleSAMLSessionID',
    'session.cookie.lifetime' => (int)(getenv('SIMPLESAML_SESSION_LIFETIME') ?: '0'),
    'session.cookie.path' => '/',
    'session.cookie.domain' => getenv('SIMPLESAML_COOKIE_DOMAIN') ?: '.your-domain.com',
    'session.cookie.secure' => filter_var(getenv('SIMPLESAML_COOKIE_SECURE') ?: 'true', FILTER_VALIDATE_BOOLEAN),

    // Language settings
    'language.available' => ['en'],
    'language.rtl' => [],
    'language.default' => 'en',

    // Module configuration
    'module.enable' => [
        'core' => true,
        'admin' => true,
        'saml' => true,
        'exampleauth' => filter_var(getenv('SIMPLESAML_ENABLE_EXAMPLE_AUTH') ?: 'false', FILTER_VALIDATE_BOOLEAN),
    ],


    // Store configuration
    'store.type' => 'redis', 

    'store.redis.host' => 'ha-redis-staging.wbueu6.ng.0001.use1.cache.amazonaws.com',
    'store.redis.port' => (int) (getenv('SIMPLESAML_REDIS_PORT') ?: 6379),
    'store.redis.database' => 8,

    // Strongly recommended if Drupal and SimpleSAMLphp share one Redis server.
    'store.redis.prefix' => getenv('SIMPLESAML_REDIS_PREFIX') ?: 'simplesaml:',

    // If using Redis auth:
    'store.redis.username' => getenv('SIMPLESAML_REDIS_USERNAME') ?: null, // Redis 6+ ACL
    'store.redis.password' => getenv('SIMPLESAML_REDIS_PASSWORD') ?: null,

    // Logging - environment-driven
    'logging.level' => getenv('SIMPLESAML_LOG_LEVEL') ?: Logger::NOTICE,
    'logging.handler' => getenv('SIMPLESAML_LOG_HANDLER') ?: 'file',
    'logging.logfile' => getenv('SIMPLESAML_LOG_FILE') ?: 'simplesamlphp.log',

    // Development/Production mode
    'debug' => ['enabled' => filter_var(getenv('SIMPLESAML_DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN),
    'show_errors' => filter_var(getenv('SIMPLESAML_SHOW_ERRORS') ?: 'false', FILTER_VALIDATE_BOOLEAN),
    'errorreporting' => filter_var(getenv('SIMPLESAML_ERROR_REPORTING') ?: 'false', FILTER_VALIDATE_BOOLEAN) ],

    // Timezone
    'timezone' => getenv('TZ') ?: 'UTC',
];
