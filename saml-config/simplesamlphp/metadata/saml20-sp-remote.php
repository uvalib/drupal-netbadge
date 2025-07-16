<?php

/**
 * SAML 2.0 remote SP metadata for SimpleSAMLphp.
 * 
 * This file contains metadata for Service Providers (SP) that this 
 * Identity Provider (IDP) should trust.
 */

// DHPortal Development SP
$metadata['https://drupal-dhportal.ddev.site/simplesaml/module.php/saml/sp/metadata.php/default-sp'] = [
    'AssertionConsumerService' => 'https://drupal-dhportal.ddev.site/simplesaml/module.php/saml/sp/saml2-acs.php/default-sp',
    'SingleLogoutService' => 'https://drupal-dhportal.ddev.site/simplesaml/module.php/saml/sp/saml2-logout.php/default-sp',
    
    // Trust the SP certificate
    'keys' => [
        [
            'encryption' => false,
            'signing' => true,
            'type' => 'X509Certificate',
            'X509Certificate' => file_get_contents(__DIR__ . '/../cert/sp-dhportal-dev.crt'),
        ],
    ],
    
    // Attribute mapping
    'attributes' => [
        'uid',
        'cn', 
        'mail',
        'eduPersonPrincipalName',
        'eduPersonAffiliation',
    ],
    
    // Enable signature validation
    'sign.assertion' => true,
    'sign.response' => true,
    'validate.signature' => true,
];
