<?php
/**
 * Drupal NetBadge Development Environment
 * 
 * This is a placeholder index file for the DDEV environment.
 * Replace this with your actual Drupal installation when ready.
 */

phpinfo();

echo "<h1>🚀 Drupal NetBadge Environment</h1>";
echo "<p>✅ DDEV is running successfully!</p>";
echo "<p>🔧 This is a generic PHP environment ready for Drupal 10 integration.</p>";
echo "<p>🔗 <a href='/simplesaml/'>Access SimpleSAMLphp</a> (when configured)</p>";

// Display environment info
echo "<h2>Environment Information</h2>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Server:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";

// Check for SimpleSAMLphp
if (file_exists('/var/simplesamlphp')) {
    echo "<p>✅ <strong>SimpleSAMLphp:</strong> Available at /var/simplesamlphp</p>";
} else {
    echo "<p>⚠️ <strong>SimpleSAMLphp:</strong> Not found</p>";
}

echo "<h2>Next Steps</h2>";
echo "<ul>";
echo "<li>Install Drupal 10 in this web directory</li>";
echo "<li>Configure SimpleSAMLphp integration</li>";
echo "<li>Set up NetBadge authentication</li>";
echo "</ul>";
?>
