#!/bin/bash

FIXTURE=$TRAVIS_BUILD_DIR/tests/fixtures/$1.php.gz

if [ -f $FIXTURE ]; then
    drush sql:drop --yes
    php core/scripts/db-tools.php import $FIXTURE

    drush php:script $TRAVIS_BUILD_DIR/tests/update.php
    # For reasons I don't understand, doing this after reinstalling all modules
    # causes a database exception because certain tables have not been updated
    # yet. So, run database updates now instead of later.
    drush updatedb --yes

    # # Reinstall modules which were blown away by the database restore.
    orca fixture:enable-modules
fi

drush update:lightning --no-interaction --yes

drush config:export --yes
drush site:install --yes --existing-config --account-pass admin

drush config:set moderation_dashboard.settings redirect_on_login 1 --yes

orca fixture:backup --force
