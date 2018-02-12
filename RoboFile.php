<?php

class RoboFile extends \Robo\Tasks {

  protected function taskDrupal($command, $console = NULL) {
    return $this->taskExec($console ?: '../vendor/bin/drupal')
      ->rawArg($command)
      ->dir('docroot');
  }

  protected function taskDrush($command, $drush = NULL) {
    return $this->taskExec($drush ?: '../vendor/bin/drush')
      ->rawArg($command)
      ->dir('docroot');
  }

  /**
   * Updates from a previous version of Lightning.
   *
   * @param string $version
   *   The version from which to update.
   *
   * @see ::restore()
   *
   * @return \Robo\Contract\TaskInterface|NULL
   *   The task(s) to run, or NULL if the specified version is invalid.
   */
  public function update($version) {
    $tasks = $this->restore($version);

    if ($tasks) {
      $tasks
        ->addTask(
          $this->taskDrush('updatedb')->option('yes')
        )
        ->addTask(
          $this->taskDrupal('update:lightning')->option('no-interaction')->arg($version)
        );
    }
    return $tasks;
  }

  /**
   * Restores a database dump of a previous version of Lightning.
   *
   * @param string $version
   *   The semantic version from which to restore, e.g. 2.1.7. A dump of this
   *   version must exist in the tests/fixtures directory, named like
   *   $version.sql.bz2.
   *
   * @return \Robo\Contract\TaskInterface|NULL
   *   The task(s) to run, or NULL if the fixture does not exist.
   */
  public function restore($version) {
    $fixture = "tests/fixtures/$version.sql";

    if (file_exists("$fixture.bz2")) {
      return $this->collectionBuilder()
        ->addTask(
          $this->taskExec('bunzip2')->arg("$fixture.bz2")->option('keep')->option('force')
        )
        ->addTask(
          $this->taskDrupal('database:restore')->option('file', "../$fixture")
        )
        ->completion(
          $this->taskFilesystemStack()->remove($fixture)
        );
    }
    else {
      $this->say("$version fixture does not exist.");
    }
  }

}
