#!/bin/sh
#
# Cloud Hook: Reinstall Headless Lightning
#
# Run `drush site-install headless_lightning` in the target environment.

site="$1"
target_env="$2"

# Fresh install of Headless Lightning.
drush @$site.$target_env site-install headless_lightning --yes --account-pass=admin --site-name='Headless Lightning - Nightly Build'
