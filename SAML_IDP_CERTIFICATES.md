# SAML Test IDP Certificate Management

## 🏢 drupal-netbadge as Test Identity Provider

The `drupal-netbadge` project serves as a **test Identity Provider (IDP)** for local SAML development. It coordinates with `drupal-dhportal` (Service Provider) to create a complete testing ecosystem.

## 🔗 Coordinated Certificate Setup

### Automatic Setup (Recommended)

The easiest way to set up certificates for both projects is using the coordinated ecosystem script:

```bash
# Run from drupal-dhportal project
cd ../drupal-dhportal
./scripts/setup-dev-saml-ecosystem.sh ../drupal-netbadge
```

This automatically:
- 🏢 Generates IDP certificates for this project
- 📋 Generates SP certificates for drupal-dhportal
- 🔗 Configures trust relationships between both
- 📝 Provides complete testing instructions

### What Gets Created

#### In this project (drupal-netbadge):
```
saml-config/simplesamlphp/
├── cert/
│   ├── server.crt              # IDP public certificate
│   ├── server.key              # IDP private key
│   ├── server.pem              # IDP private key (PEM format)
│   └── sp-dhportal-dev.crt     # Trusted SP certificate
└── metadata/
    └── saml20-sp-remote.php    # SP trust configuration
```

#### Certificate Details:
- **Purpose**: Test IDP for local SAML development
- **Validity**: 30 days (development only)
- **Domain**: drupal-netbadge.ddev.site
- **Trust**: Automatically trusts dhportal SP certificates

## 🚀 Testing Workflow

### 1. Setup Ecosystem
```bash
# From drupal-dhportal (automatically starts both DDEV containers)
./scripts/setup-dev-saml-ecosystem.sh ../drupal-netbadge
```

### 2. Test SAML Flow (Containers Already Running)
1. Visit: https://drupal-dhportal.ddev.site/saml_login
2. Redirected to: https://drupal-netbadge.ddev.site:8443/simplesaml/...
3. Login with test credentials
4. Redirected back to dhportal with authentication

### 3. Cleanup When Done
```bash
# From drupal-dhportal
./scripts/setup-dev-saml-ecosystem.sh cleanup
```

## 🔧 Manual Certificate Management

If you need to manage IDP certificates manually:

### Generate IDP Certificates Only
```bash
# In this project root
openssl genrsa -out saml-config/simplesamlphp/cert/server.key 2048
openssl req -new -x509 -days 30 \
  -key saml-config/simplesamlphp/cert/server.key \
  -out saml-config/simplesamlphp/cert/server.crt \
  -subj "/C=US/ST=Virginia/L=Charlottesville/O=University of Virginia/OU=NetBadge Test IDP/CN=drupal-netbadge.ddev.site"

# Copy key to PEM format for SimpleSAMLphp
cp saml-config/simplesamlphp/cert/server.key saml-config/simplesamlphp/cert/server.pem
```

### Cleanup IDP Certificates
```bash
rm -f saml-config/simplesamlphp/cert/server.*
rm -f saml-config/simplesamlphp/cert/sp-*.crt
rm -f saml-config/simplesamlphp/metadata/saml20-sp-remote.php
```

## 🛡️ Security Notes

- 🚮 **Development Only**: These certificates are for local testing only
- 🔄 **30-Day Expiry**: Certificates automatically expire to prevent misuse
- 🏠 **Local Trust**: Trust relationships only work in local DDEV environment
- 🚫 **Never Production**: Never use these certificates in staging/production

## 📋 IDP Configuration

### Default IDP Metadata URL
```
https://drupal-netbadge.ddev.site:8443/simplesaml/saml2/idp/metadata.php
```

### Default Entity ID
```
https://drupal-netbadge.ddev.site:8443/simplesaml/saml2/idp/metadata.php
```

### Test User Accounts
Configured in `authsources.php` - see SimpleSAMLphp documentation for test users.

## 🔄 Certificate Lifecycle

1. **Generate**: Using ecosystem script or manually
2. **Install**: Certificates placed in SimpleSAMLphp cert directory
3. **Configure**: SP trust relationships configured automatically
4. **Test**: Full SAML authentication flow
5. **Cleanup**: All certificates removed when done

## 🤝 Integration with drupal-dhportal

This project works as a **test IDP** for the `drupal-dhportal` Service Provider:

- **IDP Role**: Authenticates users and sends SAML assertions
- **SP Role**: dhportal receives and validates assertions
- **Trust**: Both projects trust each other's certificates
- **Flow**: User logs in here, gets redirected back to dhportal

The coordinated certificate management ensures both projects work together seamlessly for local SAML development and testing.
