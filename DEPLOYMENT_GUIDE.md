# Container Deployment Configuration Guide

This guide explains how to deploy your SimpleSAMLphp-enabled container with proper configuration management.

## Configuration Methods

### 1. Environment Variables (Recommended)

The container is designed to be configured entirely through environment variables, making it suitable for modern container orchestration platforms.

#### Key Environment Variables

**Required Configuration:**
```bash
SIMPLESAML_SECRET_SALT=your-very-long-random-secret-salt-here
SIMPLESAML_ADMIN_PASSWORD=your-secure-admin-password
SIMPLESAML_BASE_URL=https://your-domain.com/simplesaml/
SIMPLESAML_SP_ENTITY_ID=https://your-domain.com
```

**IdP Configuration:**
```bash
SIMPLESAML_IDP_ENTITY_ID=https://idp.university.edu/simplesaml/saml2/idp/metadata.php
SIMPLESAML_IDP_SSO_URL=https://idp.university.edu/simplesaml/saml2/idp/SSOService.php
SIMPLESAML_IDP_SLO_URL=https://idp.university.edu/simplesaml/saml2/idp/SingleLogoutService.php
```

**Security & Production Settings:**
```bash
SIMPLESAML_PROTECT_INDEX=true
SIMPLESAML_PROTECT_METADATA=true
SIMPLESAML_COOKIE_SECURE=true
SIMPLESAML_DEBUG=false
SIMPLESAML_ENABLE_EXAMPLE_AUTH=false
```

### 2. Volume Mounts for Certificates

Mount certificates and persistent data:
```bash
# Certificates (read-only)
-v /host/certs:/var/simplesamlphp/cert:ro

# Persistent data
-v /host/data:/var/simplesamlphp/data
-v /host/logs:/var/simplesamlphp/log
```

## Deployment Examples

### Docker Run

```bash
docker run -d \
  --name drupal-netbadge \
  -p 80:80 \
  -e SIMPLESAML_SECRET_SALT="your-secret-salt" \
  -e SIMPLESAML_ADMIN_PASSWORD="your-password" \
  -e SIMPLESAML_BASE_URL="https://your-domain.com/simplesaml/" \
  -e SIMPLESAML_SP_ENTITY_ID="https://your-domain.com" \
  -e SIMPLESAML_IDP_ENTITY_ID="https://idp.university.edu/metadata" \
  -e SIMPLESAML_IDP_SSO_URL="https://idp.university.edu/sso" \
  -e SIMPLESAML_PROTECT_INDEX="true" \
  -e SIMPLESAML_DEBUG="false" \
  -v /host/certs:/var/simplesamlphp/cert:ro \
  -v /host/data:/var/simplesamlphp/data \
  -v /host/logs:/var/simplesamlphp/log \
  your-registry/drupal-netbadge:latest
```

### Docker Compose

See `package/deployment/docker-compose.production.yml` for a complete example.

```bash
# Copy environment template
cp package/config/.env.production.example .env

# Edit .env with your values
nano .env

# Deploy
docker-compose -f package/deployment/docker-compose.production.yml up -d
```

### Kubernetes

See `package/deployment/kubernetes.yml` for a complete example.

```bash
# Create secrets
kubectl create secret generic simplesaml-secrets \
  --from-literal=secret-salt="your-secret-salt" \
  --from-literal=admin-password="your-password"

# Deploy
kubectl apply -f package/deployment/kubernetes.yml
```

### AWS ECS Fargate

1. **Store secrets in AWS Systems Manager Parameter Store:**
```bash
aws ssm put-parameter \
  --name "/drupal-netbadge/simplesaml/secret-salt" \
  --value "your-secret-salt" \
  --type "SecureString"

aws ssm put-parameter \
  --name "/drupal-netbadge/simplesaml/admin-password" \
  --value "your-password" \
  --type "SecureString"
```

2. **Use the ECS task definition:**
```bash
# Update task definition with your values
aws ecs register-task-definition \
  --cli-input-json file://package/deployment/ecs-task-definition.json
```

### AWS CodePipeline Integration

Your existing `pipeline/buildspec.yml` will automatically build and push the container with the production configuration.

## Security Best Practices

### 1. Secret Management
- **Never** put secrets in environment files committed to git
- Use dedicated secret management systems:
  - AWS Systems Manager Parameter Store / Secrets Manager
  - Kubernetes Secrets
  - HashiCorp Vault
  - Azure Key Vault

### 2. Certificate Management
- Mount certificates as read-only volumes
- Use proper certificate rotation
- Store certificates in encrypted storage

### 3. Production Settings
```bash
SIMPLESAML_DEBUG=false
SIMPLESAML_SHOW_ERRORS=false
SIMPLESAML_ERROR_REPORTING=false
SIMPLESAML_ENABLE_EXAMPLE_AUTH=false
SIMPLESAML_PROTECT_INDEX=true
SIMPLESAML_PROTECT_METADATA=true
SIMPLESAML_COOKIE_SECURE=true
```

### 4. Logging
- Configure centralized logging
- Use structured logs for monitoring
- Set appropriate log levels for production

## Configuration Validation

The container includes configuration validation. Check logs on startup:

```bash
# Check container logs
docker logs drupal-netbadge

# Common issues:
# - Missing required environment variables
# - Invalid certificate paths
# - Permission issues with mounted volumes
```

## Monitoring

### Health Checks
The container includes health check endpoints:
- `/health` - Container health
- `/simplesaml/` - SimpleSAMLphp availability

### Metrics
Monitor these key metrics:
- Container resource usage
- SimpleSAMLphp authentication success/failure rates
- Certificate expiration dates
- Log error rates

## Backup Strategy

### What to Backup
- SimpleSAMLphp configuration (if using file-based config)
- Certificates and keys
- Session data (if using file-based sessions)
- Application logs for auditing

### What NOT to Backup
- Temporary files (`/tmp/simplesamlphp/`)
- Cache files
- Lock files

## Troubleshooting

### Common Issues

1. **Permission Errors**
   - Ensure mounted directories are writable by `www-data` (UID 33)
   
2. **Configuration Errors**
   - Check all required environment variables are set
   - Validate certificate paths and permissions
   
3. **Network Issues**
   - Verify firewall rules for SAML endpoints
   - Check DNS resolution for IdP URLs

### Debug Mode

For troubleshooting, temporarily enable debug mode:
```bash
SIMPLESAML_DEBUG=true
SIMPLESAML_SHOW_ERRORS=true
SIMPLESAML_LOG_LEVEL=DEBUG
```

**Remember to disable debug mode in production!**
