#!/usr/bin/env bash

SITE_DIR=$(pwd)/docroot/sites/default
SETTINGS=$SITE_DIR/settings.php

DB_URL=${DB_URL:-sqlite://db.sqlite}

# Delete previous settings.
if [[ -f $SETTINGS ]]; then
    chmod +w $SITE_DIR $SETTINGS
    rm $SETTINGS
fi

# Install Drupal.
drush site:install standard --yes --account-pass admin --db-url $DB_URL
drush pm:enable headless_ui access_ui content_model_ui json_content

# Make settings writable.
chmod +w $SITE_DIR $SETTINGS

# Copy development settings into the site directory and require them.
cp settings.local.php $SITE_DIR
echo "require __DIR__ . '/settings.local.php';" >> $SETTINGS

# Copy PHPUnit configuration into core directory.
cp -f phpunit.xml ./docroot/core
