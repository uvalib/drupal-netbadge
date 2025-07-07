#!/bin/bash

#
# Container Sync Script
# Ensures DDEV and AWS containers stay in sync
#

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}🔄 Syncing containers between DDEV and AWS Pipeline...${NC}"

# Get current git commit
COMMIT_HASH=$(git rev-parse --short HEAD)
BUILD_VERSION=$(date +"%Y%m%d%H%M%S")

echo -e "${YELLOW}📦 Building container for commit: ${COMMIT_HASH}${NC}"

# Build the container locally (same as AWS pipeline)
docker build -f package/Dockerfile -t drupal-netbadge:latest --build-arg BUILD_TAG=$BUILD_VERSION .
docker tag drupal-netbadge:latest drupal-netbadge:build-$BUILD_VERSION
docker tag drupal-netbadge:latest drupal-netbadge:gitcommit-$COMMIT_HASH

echo -e "${GREEN}✅ Container built successfully${NC}"

# Test the container locally
echo -e "${YELLOW}🧪 Testing container...${NC}"
docker run --rm -d --name drupal-netbadge-test -p 8888:80 drupal-netbadge:latest
sleep 5

# Basic health check
if curl -f http://localhost:8888 > /dev/null 2>&1; then
    echo -e "${GREEN}✅ Container health check passed${NC}"
else
    echo -e "${RED}❌ Container health check failed${NC}"
fi

# Cleanup test container
docker stop drupal-netbadge-test > /dev/null 2>&1 || true

# Update DDEV to use the new container
echo -e "${YELLOW}🔄 Updating DDEV configuration...${NC}"
ddev restart

echo -e "${GREEN}🎉 Container sync completed!${NC}"
echo -e "${YELLOW}📝 Container tags created:${NC}"
echo -e "   • drupal-netbadge:latest"
echo -e "   • drupal-netbadge:build-$BUILD_VERSION"
echo -e "   • drupal-netbadge:gitcommit-$COMMIT_HASH"
echo ""
echo -e "${YELLOW}� Available npm commands:${NC}"
echo -e "   • npm run deploy:check  - Check deployment readiness"
echo -e "   • npm run aws:login     - Login to AWS ECR"
echo -e "   • npm run aws:push      - Push to ECR manually"
echo ""
echo -e "${YELLOW}�🚀 To deploy to AWS, push your changes and the pipeline will use the same container configuration.${NC}"
