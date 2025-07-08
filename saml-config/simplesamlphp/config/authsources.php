<?php
/**
 * Authentication sources configuration for DDEV testing
 */

$config = [
    // Default SP for testing
    'default-sp' => [
        'saml:SP',
        'entityID' => 'https://drupal-netbadge.ddev.site:8443',
        'idp' => 'example-userpass',
        'discoURL' => null,
    ],

    // drupal-dhportal Service Provider
    'dhportal-sp' => [
        'saml:SP',
        'entityID' => 'https://drupal-dhportal.ddev.site:8443',
        'idp' => 'example-userpass',
        'discoURL' => null,
    ],

    // Simple userpass authentication for testing
    'example-userpass' => [
        'exampleauth:UserPass',
        'student:studentpass' => [
            'uid' => ['student'],
            'eduPersonPrincipalName' => ['student@example.edu'],
            'eduPersonAffiliation' => ['member', 'student'],
            'eduPersonScopedAffiliation' => ['student@example.edu'],
            'mail' => ['student@example.edu'],
            'displayName' => ['Test Student'],
            'givenName' => ['Test'],
            'sn' => ['Student'],
        ],
        'staff:staffpass' => [
            'uid' => ['staff'],
            'eduPersonPrincipalName' => ['staff@example.edu'],
            'eduPersonAffiliation' => ['member', 'staff'],
            'eduPersonScopedAffiliation' => ['staff@example.edu'],
            'mail' => ['staff@example.edu'],
            'displayName' => ['Test Staff'],
            'givenName' => ['Test'],
            'sn' => ['Staff'],
        ],
        'faculty:facultypass' => [
            'uid' => ['faculty'],
            'eduPersonPrincipalName' => ['faculty@example.edu'],
            'eduPersonAffiliation' => ['member', 'faculty'],
            'eduPersonScopedAffiliation' => ['faculty@example.edu'],
            'mail' => ['faculty@example.edu'],
            'displayName' => ['Test Faculty'],
            'givenName' => ['Test'],
            'sn' => ['Faculty'],
        ],
    ],

    // NetBadge configuration (for future use)
    'netbadge' => [
        'saml:SP',
        'entityID' => 'https://drupal-netbadge.ddev.site:8443',
        'idp' => 'netbadge-idp',
        'discoURL' => null,
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient',
    ],
];
