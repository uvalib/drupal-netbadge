# SimpleSAMLphp Testing Guide

This guide will help you test the SimpleSAMLphp SAML authentication flow in your DDEV environment.

## Quick Start

1. **Access SimpleSAMLphp Admin Interface**
   - URL: https://drupal-netbadge.ddev.site:8443/simplesaml
   - Admin password: `admin123`

2. **Test SAML Authentication Flow**
   - URL: https://drupal-netbadge.ddev.site:8443/test-saml.php
   - This will initiate the SAML login process

## Test User Accounts

The following test accounts are configured in the userpass IdP:

### Student Account
- **Username**: `student`
- **Password**: `studentpass`
- **Attributes**:
  - uid: student
  - eduPersonAffiliation: member, student
  - eduPersonScopedAffiliation: student@example.edu
  - mail: student@example.edu
  - displayName: Test Student

### Staff Account
- **Username**: `staff`
- **Password**: `staffpass`
- **Attributes**:
  - uid: staff
  - eduPersonAffiliation: member, staff
  - eduPersonScopedAffiliation: staff@example.edu
  - mail: staff@example.edu
  - displayName: Test Staff Member

### Faculty Account
- **Username**: `faculty`
- **Password**: `facultypass`
- **Attributes**:
  - uid: faculty
  - eduPersonAffiliation: member, faculty
  - eduPersonScopedAffiliation: faculty@example.edu
  - mail: faculty@example.edu
  - displayName: Test Faculty

## Testing Steps

1. **Access the SimpleSAMLphp Admin Interface**
   - Go to https://drupal-netbadge.ddev.site:8443/simplesaml
   - Click on "Authentication" tab
   - Select "Test configured authentication sources"
   - Choose "example-userpass" from the list
   - Use any of the test accounts above to authenticate

2. **Test the SAML SP (Service Provider)**
   - Go to https://drupal-netbadge.ddev.site:8443/test-saml.php
   - Click "Click here to login with SAML"
   - You'll be redirected to the userpass authentication page
   - Use any test account credentials
   - After successful authentication, you'll see the user attributes

3. **Test Metadata**
   - IdP Metadata: https://drupal-netbadge.ddev.site:8443/simplesaml/saml2/idp/metadata.php
   - SP Metadata: https://drupal-netbadge.ddev.site:8443/simplesaml/module.php/saml/sp/metadata.php/default-sp

## Configuration Files

- **Main Config**: `vendor/simplesamlphp/simplesamlphp/config/config.php`
- **Auth Sources**: `vendor/simplesamlphp/simplesamlphp/config/authsources.php`
- **Original Config**: `ddev-simplesaml-config.php`
- **Original Auth Sources**: `ddev-authsources.php`

## Troubleshooting

If you encounter issues:

1. **Check DDEV logs**:
   ```bash
   ddev logs
   ```

2. **Check SimpleSAMLphp logs**:
   ```bash
   ddev exec "tail -f /var/simplesamlphp/log/simplesamlphp.log"
   ```

3. **Verify configuration**:
   ```bash
   ddev exec "cd /var/www/html && vendor/bin/simplesamlphp config:check"
   ```

4. **Reset SimpleSAMLphp session**:
   - Go to https://drupal-netbadge.ddev.site:8443/simplesaml/logout.php

## Next Steps

Once SAML authentication is working, you can:

1. Integrate with your PHP application
2. Configure additional Identity Providers
3. Customize user attributes mapping
4. Set up production SAML configuration
