# Drupal NetBadge with SimpleSAMLphp

This project provides a Drupal installation integrated with SimpleSAMLphp for NetBadge authentication, designed to work seamlessly in both DDEV development environments and AWS CodePipeline deployments.

## Quick Start

### Prerequisites

- [DDEV](https://ddev.readthedocs.io/en/stable/)
- Docker
- Git
- Make (optional, for convenience commands)

### Local Development Setup

1. **Clone and setup the project:**
   ```bash
   git clone <your-repo-url>
   cd drupal-netbadge
   npm install
   cp .env.example .env
   # Edit .env with your configuration
   ```

2. **Start DDEV:**
   ```bash
   npm run start
   # or
   ddev start
   ```

3. **Build and sync containers:**
   ```bash
   npm run sync
   # or for initial setup
   npm run dev:setup
   ```

4. **Access your site:**
   - Drupal: https://drupal-netbadge.ddev.site
   - SimpleSAMLphp: https://drupal-netbadge.ddev.site:8080

### Development Workflow

#### Container Synchronization

To keep your local development environment in sync with what will be deployed to AWS:

```bash
# Build and test the same container used in AWS
npm run sync

# Or for a complete rebuild
npm run dev:rebuild
```

This command:
- Builds the container using the same Dockerfile as AWS
- Tags it with git commit and timestamp
- Tests the container locally
- Updates DDEV to use the new container

#### Available Commands

```bash
npm run help          # Show all available commands
npm run build         # Build container locally
npm run sync          # Sync containers between DDEV and AWS
npm run start         # Start DDEV
npm run stop          # Stop DDEV
npm run restart       # Restart DDEV
npm run logs          # Show DDEV logs
npm run clean         # Clean up containers and volumes
npm run test          # Run tests
npm run deploy:check  # Check if ready for deployment
npm run dev:setup     # Initial development setup
npm run dev:rebuild   # Clean rebuild everything
```

## Architecture

### Container Strategy

This project uses a unified container strategy:

1. **Single Dockerfile** (`package/Dockerfile`): Used by AWS CodePipeline for production
2. **Base Image**: `cirrusid/simplesamlphp:latest` provides SimpleSAMLphp pre-configured
3. **DDEV Development**: Uses standard DDEV containers with SimpleSAMLphp configuration mounted
4. **Environment Variables**: Used for environment-specific differences

### DDEV Configuration

- **Type**: Generic PHP (compatible with Drupal 10)
- **PHP Version**: 8.2
- **Web Server**: Apache
- **Database**: MariaDB 10.11
- **SimpleSAMLphp**: Configuration mounted as volumes

### AWS Pipeline

The `pipeline/buildspec.yml` handles:
- Container building with build tags
- ECR authentication and pushing
- SSM parameter updates for deployment tracking

## SimpleSAMLphp Configuration

### Configuration Files

- `simplesamlphp/config/config.php`: Main SimpleSAMLphp configuration
- `simplesamlphp/config/authsources.php`: Authentication sources including NetBadge
- `simplesamlphp/metadata/`: SAML metadata files

### NetBadge Integration

The project includes a pre-configured authentication source for NetBadge:

```php
'netbadge' => [
    'netbadge:NetBadge',
    'auth_url' => getenv('NETBADGE_AUTH_URL'),
    'validate_url' => getenv('NETBADGE_VALIDATE_URL'),
    'logout_url' => getenv('NETBADGE_LOGOUT_URL'),
],
```

## Deployment

### To AWS

1. **Ensure containers are synced:**
   ```bash
   npm run sync
   npm run deploy:check
   ```

2. **Commit and push your changes:**
   ```bash
   git add .
   git commit -m "Your changes"
   git push
   ```

3. **AWS CodePipeline will automatically:**
   - Build the same container
   - Tag with timestamp and git commit
   - Push to ECR
   - Update SSM parameters

### Environment Variables

Set these in your AWS environment:

- `AWS_REGION`: Your AWS region
- `CONTAINER_REGISTRY`: Your ECR registry URL
- `CONTAINER_IMAGE`: Container image name
- SimpleSAMLphp and NetBadge configuration variables

## Security Notes

1. **Change default passwords** in `.env`
2. **Use proper SSL certificates** in production
3. **Secure SimpleSAMLphp secret salt**
4. **Review NetBadge URLs** for your institution

## Troubleshooting

### Container Issues

```bash
# Rebuild everything
npm run dev:rebuild

# Check container health
npm run container:health
```

### DDEV Issues

```bash
# Reset DDEV
npm run clean
npm run start

# Check DDEV status
ddev describe
```

### SimpleSAMLphp Issues

- Check configuration in `simplesamlphp/config/`
- Verify environment variables in `.env`
- Access SimpleSAMLphp admin at `/simplesaml/`

## Contributing

1. Make changes locally using DDEV
2. Test with `npm run sync` to ensure AWS compatibility
3. Run `npm run deploy:check` before committing
4. Submit pull requests

## Support

For issues specific to:
- **DDEV**: Check [DDEV documentation](https://ddev.readthedocs.io/)
- **SimpleSAMLphp**: Check [SimpleSAMLphp documentation](https://simplesamlphp.org/docs/)
- **NetBadge**: Contact your institution's IT department
