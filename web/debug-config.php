<?php
// Debug config loading for netbadge IdP
echo "<h1>SimpleSAMLphp Debug Config Test</h1>";

try {
    // Try to load SimpleSAMLphp from vendor
    $vendorPath = '/var/www/html/vendor/simplesamlphp/simplesamlphp/src/_autoload.php';
    if (file_exists($vendorPath)) {
        echo "<p>Loading from vendor: $vendorPath</p>";
        require_once($vendorPath);
    } else {
        echo "<p>Vendor autoload not found: $vendorPath</p>";
    }
    
    // Try to load SimpleSAMLphp from simplesamlphp directory
    $simplePath = '/var/www/html/simplesamlphp/lib/_autoload.php';
    if (file_exists($simplePath)) {
        echo "<p>Loading from simplesamlphp: $simplePath</p>";
        require_once($simplePath);
    } else {
        echo "<p>SimpleSAMLphp autoload not found: $simplePath</p>";
    }
    
    // Load configuration
    $config = SimpleSAML\Configuration::getInstance();
    
    echo "<h2>Config Instance Details:</h2>";
    echo "<p>Config class: " . get_class($config) . "</p>";
    
    echo "<h2>Debug Setting:</h2>";
    $debug = $config->getOptionalValue('debug', 'NOT_SET');
    echo "<p>Debug value: " . var_export($debug, true) . "</p>";
    echo "<p>Debug type: " . gettype($debug) . "</p>";
    
    echo "<h2>All Config Values:</h2>";
    $allConfig = $config->toArray();
    echo "<pre>";
    foreach ($allConfig as $key => $value) {
        echo "$key: " . var_export($value, true) . "\n";
    }
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace:</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
