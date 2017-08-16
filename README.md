[![Build Status](https://travis-ci.org/acquia/headless-lightning.svg?branch=master)](https://travis-ci.org/acquia/lightning)

# Headless Lightning
Headless Lightning is a more opinionated version of the [Lightning Drupal](https://github.com/acquia/lightning)
distribution as a backend for decoupled applications.

## Quick Start
To build the codebase:

    composer create-project acquia/lightning-project:dev-headless --no-interaction

This will create a new directory, `lightning-project` which contains a `docroot`
folder. This is where you should point your web server.

Just like any Drupal project, you will need a working [LAMP stack](https://www.drupal.org/node/2461571)
and an available database within that stack. Once you have that setup, you can
use Drush, DrupalConsole, or the web interface to install the site.

**There is no need to clone or fork this project unless you want to contribute
back. Use a scaffold project like Lightning Project, BLT, or Drupal Project to
build a codebase for actual use.** See `composer create-project` command above.

### Distribution
Headless Lightning is currently distributed via [PHP Packagist](https://packagist.org/packages/acquia/headless_lightning)
and is maintained on [GitHub](https://github.com/acquia/headless-lightning).

## Contributing
Headless Lightning provides some phing targets to aid in development. To take
advantage:

1. Clone this project.
2. Build working codebase (`composer install`).
3. Install dev environment (`phing install` - common options: `-Ddb.database={DB_NAME} -Ddb.user{DB_USER} -Ddb.password={DB_PASS} -Durl=http://{LOCAL_ENV}`)
4. Make changes in `/docroot/profiles/headless_lightning`.
5. Once you're happy with changes, use `phing pull` to move your changes back
   into the top-level repository to be committed or submitted as a PR or patch.
   
Please file issues in our [GitHub Issue Queue](https://github.com/acquia/headless-lightning/issues).

## Goals
**Headless Lightning aims to provide a standard backend content repository that
allows for easy ingestion by decoupled applications.** It does so by building on
and configuring the basic tool set provided by the contrib modules selected and
implemented in [Lightning's Content API](https://github.com/acquia/lightning/tree/8.x-2.x/modules/lightning_features/lightning_api).

### Headless Lightning Goals
* Make the UI more intuitive for non-Drupalists and more logical for everyone
  that uses Drupal primarily as a content store.
* Have opinions about and examples of how an external application should
  authenticate against and consume the API.
* Not get in the way of developers, site builders, or content editors. 

## Features
### JSON Content
Presentation layer blanket that generally hides or redirects users from content
rendered by the Drupal application.

### Headless UI
User Interface opinions that make managing content without rendering
implications more intuitive.

#### Headless UI Sub-components
Headless UI Sub-components consolidate links and add relevant descriptions to
common administrative tasks central to using Drupal as a decoupled backend.

* Access UI
* Content Model UI


### Built on Drupal and Lightning
Headless Lightning is built on Drupal and extends the Lightning distribution.
That means you can create a sub-profile of Headless Lightning - just like
Headless Lightning is a sub-profile of Lightning itself. It also means that
applications built with Headless Lightning will get all of the features of and
updates to Drupal and Lightning along with update paths, just like users of
Lightning. So you don't have to worry about stuff like parts of Media going into
core or Content Moderation versus Workflow Moderation. **You offload all of those
responsibilities, even in your decoupled application, to Lightning.**

## Similar Projects
*From which we have borrowed heavily and for which we are very thankful.*

* [Contenta CMS](https://github.com/contentacms)  
  **Contenta makes your content happy**  
  Key differences:
    * Contenta has an emphasis on OOTB ingestion examples and opinions, where
      Lightning is agnostic about implementation.
    * Headless Lightning provides more OOTB features like Media and Workflow -
      and provides an update/upgrade path for those features as they mature via
      Lightning.
* [Reservior](https://github.com/acquia/reservoir)  
  **A back end for your front end**  
  Key differences:
    * Reservoir isn't maintained and has no test suite.
    * Headless Lightning has fewer strong opinions about UI and access to
      standard Drupal functonality.
