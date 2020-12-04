#!/usr/bin/env bash

# NAME
#     install.sh - Create the test fixture.
#
# SYNOPSIS
#     install.sh
#
# DESCRIPTION
#     Creates the test fixture and places the SUT.

cd "$(dirname "$0")"

# Reuse ORCA's own includes.
source ../../../orca/bin/travis/_includes.sh

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
