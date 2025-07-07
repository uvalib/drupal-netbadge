#!/usr/bin/env node

/**
 * Enhanced npm-based development script
 * Cross-platform alternative to shell scripts
 */

const { exec, spawn } = require('child_process');
const fs = require('fs');
const path = require('path');

// Colors for console output
const colors = {
  red: '\x1b[31m',
  green: '\x1b[32m',
  yellow: '\x1b[33m',
  blue: '\x1b[34m',
  magenta: '\x1b[35m',
  cyan: '\x1b[36m',
  reset: '\x1b[0m'
};

function log(message, color = 'reset') {
  console.log(`${colors[color]}${message}${colors.reset}`);
}

function execAsync(command) {
  return new Promise((resolve, reject) => {
    exec(command, (error, stdout, stderr) => {
      if (error) {
        reject({ error, stderr });
      } else {
        resolve(stdout.trim());
      }
    });
  });
}

async function getGitCommit() {
  try {
    return await execAsync('git rev-parse --short HEAD');
  } catch {
    return 'unknown';
  }
}

async function getBuildVersion() {
  return new Date().toISOString().replace(/[-T:.Z]/g, '').slice(0, 14);
}

async function checkGitStatus() {
  try {
    const status = await execAsync('git status --porcelain');
    return status.length === 0;
  } catch {
    return false;
  }
}

async function checkDockerImage() {
  try {
    await execAsync('docker images | grep drupal-netbadge');
    return true;
  } catch {
    return false;
  }
}

async function healthCheck() {
  try {
    await execAsync('curl -f http://localhost:8888 > /dev/null 2>&1');
    return true;
  } catch {
    return false;
  }
}

const commands = {
  async sync() {
    log('🔄 Syncing containers between DDEV and AWS...', 'green');
    
    const commit = await getGitCommit();
    const buildVersion = await getBuildVersion();
    
    log(`📦 Building container for commit: ${commit}`, 'yellow');
    
    // Build container
    try {
      await execAsync(`docker build -f package/Dockerfile -t drupal-netbadge:latest --build-arg BUILD_TAG=${buildVersion} .`);
      await execAsync(`docker tag drupal-netbadge:latest drupal-netbadge:build-${buildVersion}`);
      await execAsync(`docker tag drupal-netbadge:latest drupal-netbadge:gitcommit-${commit}`);
      log('✅ Container built successfully', 'green');
    } catch (error) {
      log('❌ Container build failed', 'red');
      console.error(error.stderr);
      process.exit(1);
    }
    
    // Test container
    log('🧪 Testing container...', 'yellow');
    try {
      await execAsync('docker run --rm -d --name drupal-netbadge-test -p 8888:80 drupal-netbadge:latest');
      
      // Wait a moment for container to start
      await new Promise(resolve => setTimeout(resolve, 5000));
      
      const healthy = await healthCheck();
      if (healthy) {
        log('✅ Container health check passed', 'green');
      } else {
        log('❌ Container health check failed', 'red');
      }
      
      // Cleanup
      await execAsync('docker stop drupal-netbadge-test').catch(() => {});
      
    } catch (error) {
      log('⚠️  Container test had issues, but continuing...', 'yellow');
      await execAsync('docker stop drupal-netbadge-test').catch(() => {});
    }
    
    // Restart DDEV
    log('🔄 Updating DDEV configuration...', 'yellow');
    try {
      await execAsync('ddev restart');
    } catch (error) {
      log('⚠️  DDEV restart failed - you may need to run "ddev restart" manually', 'yellow');
    }
    
    log('🎉 Container sync completed!', 'green');
    log('📝 Container tags created:', 'yellow');
    log(`   • drupal-netbadge:latest`);
    log(`   • drupal-netbadge:build-${buildVersion}`);
    log(`   • drupal-netbadge:gitcommit-${commit}`);
    log('');
    log('💡 Available npm commands:', 'yellow');
    log('   • npm run deploy:check  - Check deployment readiness');
    log('   • npm run aws:login     - Login to AWS ECR');
    log('   • npm run aws:push      - Push to ECR manually');
    log('');
    log('🚀 To deploy to AWS, push your changes and the pipeline will use the same container configuration.', 'yellow');
  },
  
  async deployCheck() {
    log('✅ Checking deployment readiness...', 'blue');
    
    const gitClean = await checkGitStatus();
    const containerExists = await checkDockerImage();
    
    if (gitClean) {
      log('✅ Git status clean', 'green');
    } else {
      log('⚠️  You have uncommitted changes', 'yellow');
    }
    
    if (containerExists) {
      log('✅ Local container built', 'green');
    } else {
      log('❌ Local container not found - run "npm run build" first', 'red');
    }
    
    if (gitClean && containerExists) {
      log('🎉 Ready for deployment!', 'green');
    } else {
      log('⚠️  Not ready for deployment', 'yellow');
    }
  },
  
  help() {
    log('\n🚀 Drupal NetBadge Development Commands', 'cyan');
    log('====================================\n', 'cyan');
    log('npm run build         # Build the container locally');
    log('npm run sync          # Sync containers between DDEV and AWS');
    log('npm run start         # Start DDEV environment');
    log('npm run stop          # Stop DDEV environment');
    log('npm run restart       # Restart DDEV environment');
    log('npm run logs          # Show DDEV logs');
    log('npm run clean         # Clean up containers and volumes');
    log('npm run test          # Run tests');
    log('npm run deploy:check  # Check if ready for deployment');
    log('npm run aws:login     # Login to AWS ECR');
    log('npm run aws:push      # Push container to ECR');
    log('npm run dev:setup     # Initial development setup');
    log('npm run dev:rebuild   # Clean rebuild everything');
    log('npm run container:health # Test container health');
    log('');
  }
};

// Get command from command line arguments
const command = process.argv[2];

if (commands[command]) {
  commands[command]().catch(error => {
    log(`❌ Command failed: ${error.message}`, 'red');
    process.exit(1);
  });
} else {
  commands.help();
}
