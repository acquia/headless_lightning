#!/usr/bin/env bash

# NAME
#     install.sh - Create the test fixture.
#
# SYNOPSIS
#     install.sh
#
# DESCRIPTION
#     Creates the test fixture and places the SUT.

cd "$(dirname "$0")" || exit; source _includes.sh

CURRENT_RECOMMENDED="~9.0.0"
CURRENT_DEV="9.0.x-dev"
NEXT_DEV="9.1.x-dev"

case "$ORCA_JOB" in
  "DEPRECATED_CODE_SCAN") orca debug:packages; eval "orca fixture:init -f --sut=$ORCA_SUT_NAME --sut-only --no-site-install" ;;
  "ISOLATED_RECOMMENDED") orca debug:packages "$CURRENT_RECOMMENDED"; eval "orca fixture:init -f --sut=$ORCA_SUT_NAME --sut-only --core=$CURRENT_RECOMMENDED --profile=$ORCA_FIXTURE_PROFILE --project-template=$ORCA_FIXTURE_PROJECT_TEMPLATE" ;;
  "INTEGRATED_RECOMMENDED") orca debug:packages "$CURRENT_RECOMMENDED"; eval "orca fixture:init -f --sut=$ORCA_SUT_NAME --core=$CURRENT_RECOMMENDED --profile=$ORCA_FIXTURE_PROFILE --project-template=$ORCA_FIXTURE_PROJECT_TEMPLATE" ;;
  # "CORE_PREVIOUS" is never used.
  "ISOLATED_DEV") orca debug:packages "$CURRENT_DEV"; eval "orca fixture:init -f --sut=$ORCA_SUT_NAME --sut-only --core=$CURRENT_DEV --dev --profile=$ORCA_FIXTURE_PROFILE --project-template=$ORCA_FIXTURE_PROJECT_TEMPLATE" ;;
  "INTEGRATED_DEV") orca debug:packages "$CURRENT_DEV"; eval "orca fixture:init -f --sut=$ORCA_SUT_NAME --core=$CURRENT_DEV --dev --profile=$ORCA_FIXTURE_PROFILE --project-template=$ORCA_FIXTURE_PROJECT_TEMPLATE" ;;
  "CORE_NEXT") orca debug:packages "$NEXT_DEV"; eval "orca fixture:init -f --sut=$ORCA_SUT_NAME --core=$NEXT_DEV --dev --profile=$ORCA_FIXTURE_PROFILE --project-template=$ORCA_FIXTURE_PROJECT_TEMPLATE" ;;
esac


# Upgrade test special case:

# Exit early if no DB fixture is specified.
[[ "$DB_FIXTURE" ]] || exit 0

orca fixture:init --force --sut ${ORCA_SUT_NAME} --sut-only --no-site-install

cd "$ORCA_FIXTURE_DIR/docroot" || exit 0

# Ensure the files directory exists so that the default user avatar can be
# copied into it.
mkdir -p ./sites/default/files

DB="$TRAVIS_BUILD_DIR/tests/fixtures/$DB_FIXTURE.php.gz"

php core/scripts/db-tools.php import "$DB"

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
