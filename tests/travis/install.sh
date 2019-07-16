#!/usr/bin/env bash

# NAME
#     install.sh - Install Travis CI dependencies
#
# SYNOPSIS
#     install.sh
#
# DESCRIPTION
#     Creates the test fixture.

cd "$(dirname "$0")"; source _includes.sh

# Exit early if no DB fixture is specified.
[[ "$DB_FIXTURE" ]] || exit 0

orca fixture:init --force --sut ${ORCA_SUT_NAME} --sut-only --no-site-install

cd "$ORCA_FIXTURE_DIR/docroot"

# Ensure the files directory exists so that the default user avatar can be
# copied into it.
mkdir -p ./sites/default/files

DB="$TRAVIS_BUILD_DIR/tests/fixtures/$DB_FIXTURE.php.gz"

php core/scripts/db-tools.php import ${DB}

drush php:script "$TRAVIS_BUILD_DIR/tests/update.php"

drush updatedb --yes
drush update:lightning --no-interaction --yes

orca fixture:enable-extensions

# Reinstall from exported configuration to prove that it's coherent.
drush config:export --yes
drush site:install --yes --existing-config --account-pass admin

drush config:set moderation_dashboard.settings redirect_on_login 1 --yes

# Set the fixture state to reset to between tests.
orca fixture:backup --force
