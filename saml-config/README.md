# SAML Configuration Files

This directory contains all SimpleSAMLphp configuration files for the SAML Identity Provider (IdP) setup.

## Files

- `ddev-simplesaml-config.php` - DDEV-specific SimpleSAMLphp configuration
- `saml20-idp-hosted.php` - SAML 2.0 IdP hosted metadata
- `simplesamlphp-config/` - Additional SimpleSAMLphp configuration files
- `simplesamlphp/` - Main SimpleSAMLphp configuration directory with metadata and certificates

## Usage

These files are referenced by the SimpleSAMLphp installation and provide the IdP configuration.

## Important Notes

- Configuration files contain sensitive information like certificates and secret keys
- The `simplesamlphp/` directory contains the main configuration files used by SimpleSAMLphp
- Files are automatically loaded by SimpleSAMLphp during operation
