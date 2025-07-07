<?php
/**
 * Test SAML authentication flow
 */

// Include SimpleSAMLphp autoloader
require_once '../vendor/simplesamlphp/simplesamlphp/src/_autoload.php';

use SimpleSAML\Auth\Simple;

// Initialize SimpleSAMLphp with the default-sp configuration
$as = new Simple('default-sp');

// Check if user is authenticated
if (!$as->isAuthenticated()) {
    echo '<h1>SAML Authentication Test</h1>';
    echo '<p>You are not authenticated.</p>';
    echo '<p><a href="' . $as->getLoginURL() . '">Click here to login with SAML</a></p>';
} else {
    echo '<h1>SAML Authentication Successful!</h1>';
    
    // Get user attributes
    $attributes = $as->getAttributes();
    
    echo '<h2>User Information:</h2>';
    echo '<ul>';
    foreach ($attributes as $name => $values) {
        echo '<li><strong>' . htmlspecialchars($name) . ':</strong> ';
        if (is_array($values)) {
            echo htmlspecialchars(implode(', ', $values));
        } else {
            echo htmlspecialchars($values);
        }
        echo '</li>';
    }
    echo '</ul>';
    
    echo '<p><a href="' . $as->getLogoutURL() . '">Logout</a></p>';
}
