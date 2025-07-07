<?php
/**
 * SimpleSAMLphp authentication sources for production deployment
 * Configured via environment variables
 */

$config = [
    // Default SP configuration - environment-driven
    'default-sp' => [
        'saml:SP',
        'entityID' => getenv('SIMPLESAML_SP_ENTITY_ID') ?: 'https://your-domain.com',
        'idp' => getenv('SIMPLESAML_DEFAULT_IDP') ?: null,
        'discoURL' => getenv('SIMPLESAML_DISCO_URL') ?: null,
    ],
];

// Add SAML IdP configuration if provided via environment
$idpEntityId = getenv('SIMPLESAML_IDP_ENTITY_ID');
$idpSsoUrl = getenv('SIMPLESAML_IDP_SSO_URL');
$idpSloUrl = getenv('SIMPLESAML_IDP_SLO_URL');
$idpCertFile = getenv('SIMPLESAML_IDP_CERT_FILE');

if ($idpEntityId && $idpSsoUrl) {
    $config['production-idp'] = [
        'saml:SP',
        'entityID' => getenv('SIMPLESAML_SP_ENTITY_ID') ?: 'https://your-domain.com',
        'idp' => 'production-saml-idp',
    ];

    $config['production-saml-idp'] = [
        'saml:External',
        'entityId' => $idpEntityId,
        'singleSignOnService' => $idpSsoUrl,
        'singleLogoutService' => $idpSloUrl ?: null,
        'certificate' => $idpCertFile ?: null,
    ];
}

// Add example userpass auth only if explicitly enabled for testing
if (filter_var(getenv('SIMPLESAML_ENABLE_EXAMPLE_AUTH') ?: 'false', FILTER_VALIDATE_BOOLEAN)) {
    $config['example-userpass'] = [
        'exampleauth:UserPass',
        'student:studentpass' => [
            'uid' => ['student'],
            'eduPersonAffiliation' => ['member', 'student'],
            'eduPersonScopedAffiliation' => ['student@example.edu'],
            'mail' => ['student@example.edu'],
            'displayName' => ['Test Student'],
            'givenName' => ['Test'],
            'sn' => ['Student'],
        ],
        'staff:staffpass' => [
            'uid' => ['staff'],
            'eduPersonAffiliation' => ['member', 'staff'],
            'eduPersonScopedAffiliation' => ['staff@example.edu'],
            'mail' => ['staff@example.edu'],
            'displayName' => ['Test Staff Member'],
            'givenName' => ['Test'],
            'sn' => ['Staff'],
        ],
        'faculty:facultypass' => [
            'uid' => ['faculty'],
            'eduPersonAffiliation' => ['member', 'faculty'],
            'eduPersonScopedAffiliation' => ['faculty@example.edu'],
            'mail' => ['faculty@example.edu'],
            'displayName' => ['Test Faculty'],
            'givenName' => ['Test'],
            'sn' => ['Faculty'],
        ],
    ];
}
