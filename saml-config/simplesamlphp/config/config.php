<?php
/**
 * SimpleSAMLphp configuration for DDEV development environment
 */

use SimpleSAML\Logger;

$config = [
    // Basic configuration for DDEV
    'baseurlpath' => '/simplesaml/',
    'certdir' => '/var/simplesamlphp/cert/',
    'loggingdir' => '/var/simplesamlphp/log/',
    'datadir' => '/var/simplesamlphp/data/',
    'metadatadir' => '/var/simplesamlphp/metadata/',
    'tempdir' => '/var/simplesamlphp/tmp/',

    // Security settings
    'secretsalt' => 'defaultsecretfordevonly-changeinproduction',
    'auth.adminpassword' => 'admin123',
    'admin.protectindexpage' => false,
    'admin.protectmetadata' => false,

    // Technical contact
    'technicalcontact_name' => 'DDEV Development',
    'technicalcontact_email' => 'dev@localhost',

    // Session configuration
    'session.cookie.name' => 'SimpleSAMLSessionID',
    'session.cookie.lifetime' => 0,
    'session.cookie.path' => '/',
    'session.cookie.domain' => '.ddev.site',
    'session.cookie.secure' => false,

    // Language settings
    'language.available' => ['en'],
    'language.rtl' => [],
    'language.default' => 'en',

    // Module configuration
    'module.enable' => [
        'core' => true,
        'admin' => true,
        'saml' => true,
        'exampleauth' => true,
    ],

    // Store configuration
    'store.type' => 'phpsession',

    // Logging
    'logging.level' => Logger::NOTICE,
    'logging.handler' => 'file',
    'logging.logfile' => 'simplesamlphp.log',

    // Development settings
    'debug' => null,
    'showerrors' => true,
    'errorreporting' => true,

    // Enable SAML 2.0 IdP
    'enable.saml20-idp' => true,

    // Timezone
    'timezone' => 'America/New_York',
];
