# Custom web build for drupal-netbadge

# Install gettext-base for envsubst (required for SAML template processing)
ARG BASE_IMAGE
FROM $BASE_IMAGE
RUN apt-get update && apt-get install -y gettext-base && apt-get clean && rm -rf /var/lib/apt/lists/*
