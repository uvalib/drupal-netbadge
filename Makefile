.PHONY: help build sync start stop restart logs clean test deploy

# Default target
help: ## Show this help message
	@echo "Drupal NetBadge Development Commands"
	@echo "===================================="
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

build: ## Build the container locally
	@echo "🔨 Building container..."
	docker build -f package/Dockerfile -t drupal-netbadge:latest .

sync: ## Sync containers between DDEV and AWS
	@echo "🔄 Syncing containers..."
	./sync-containers.sh

start: ## Start DDEV environment
	@echo "🚀 Starting DDEV..."
	ddev start

stop: ## Stop DDEV environment
	@echo "⏹️  Stopping DDEV..."
	ddev stop

restart: ## Restart DDEV environment
	@echo "🔄 Restarting DDEV..."
	ddev restart

logs: ## Show DDEV logs
	@echo "📋 Showing DDEV logs..."
	ddev logs

clean: ## Clean up containers and volumes
	@echo "🧹 Cleaning up..."
	ddev delete -Oy
	docker system prune -f

test: ## Run tests
	@echo "🧪 Running tests..."
	ddev exec "cd /var/www/html && ./vendor/bin/phpunit"

deploy-check: ## Check if ready for AWS deployment
	@echo "✅ Checking deployment readiness..."
	@git status --porcelain | grep -q . && echo "⚠️  You have uncommitted changes" || echo "✅ Git status clean"
	@docker images | grep -q drupal-netbadge && echo "✅ Local container built" || echo "❌ Local container not found"

aws-login: ## Login to AWS ECR
	@echo "🔐 Logging into AWS ECR..."
	aws ecr get-login-password --region $(AWS_REGION) | docker login --username AWS --password-stdin $(CONTAINER_REGISTRY)

push-to-ecr: ## Push container to ECR (for manual deployment)
	@echo "📤 Pushing to ECR..."
	docker tag drupal-netbadge:latest $(CONTAINER_REGISTRY)/drupal-netbadge:latest
	docker push $(CONTAINER_REGISTRY)/drupal-netbadge:latest
