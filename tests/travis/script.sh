#!/usr/bin/env bash

# NAME
#     script.sh - Run tests
#
# SYNOPSIS
#     script.sh
#
# DESCRIPTION
#     Runs static code analysis and automated tests.

cd "$(dirname "$0")"; source _includes.sh

# Exit early if no fixture exists.
[[ ! -d "$ORCA_FIXTURE_DIR" ]] && exit 0

orca fixture:status
orca qa:automated-tests --sut ${ORCA_SUT_NAME} --sut-only
