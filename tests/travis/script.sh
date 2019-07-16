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

orca fixture:status
orca qa:automated-tests --sut ${ORCA_SUT_NAME} --sut-only
