<?php
/**
 * SimpleSAMLphp configuration for production deployment
 * Uses environment variables for container deployment
 */

use SimpleSAML\Logger;

$config = [
    // Basic configuration - environment-driven
    'baseurlpath' => getenv('SIMPLESAML_BASE_URL') ?: 'https://your-domain.com/simplesaml/',
    'certdir' => '/var/simplesamlphp/cert/',
    'loggingdir' => '/var/simplesamlphp/log/',
    'datadir' => '/var/simplesamlphp/data/',
    'tempdir' => '/tmp/simplesamlphp/',

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
    'store.type' => getenv('SIMPLESAML_STORE_TYPE') ?: 'phpsession',

    // Logging - environment-driven
    'logging.level' => getenv('SIMPLESAML_LOG_LEVEL') ?: Logger::NOTICE,
    'logging.handler' => getenv('SIMPLESAML_LOG_HANDLER') ?: 'file',
    'logging.logfile' => getenv('SIMPLESAML_LOG_FILE') ?: 'simplesamlphp.log',

    // Development/Production mode
    'debug' => filter_var(getenv('SIMPLESAML_DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN),
    'showerrors' => filter_var(getenv('SIMPLESAML_SHOW_ERRORS') ?: 'false', FILTER_VALIDATE_BOOLEAN),
    'errorreporting' => filter_var(getenv('SIMPLESAML_ERROR_REPORTING') ?: 'false', FILTER_VALIDATE_BOOLEAN),

    // Timezone
    'timezone' => getenv('TZ') ?: 'UTC',
];
