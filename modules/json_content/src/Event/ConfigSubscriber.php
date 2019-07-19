<?php

namespace Drupal\json_content\Event;

use Drupal\Core\Config\ConfigCrudEvent;
use Drupal\Core\Config\ConfigEvents;
use Drupal\Core\Menu\LocalTaskManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Reacts to config-related events.
 *
 * @internal
 *   This is an internal part of Headless Lightning and may be changed or
 *   removed at any time without warning. External code should not extend or
 *   use this class in any way!
 */
final class ConfigSubscriber implements EventSubscriberInterface {

  /**
   * The local task plugin manager.
   *
   * @var \Drupal\Core\Menu\LocalTaskManager
   */
  private $localTaskManager;

  /**
   * ConfigSubscriber constructor.
   *
   * @param \Drupal\Core\Menu\LocalTaskManager $local_task_manager
   *   The local task plugin manager.
   */
  public function __construct(LocalTaskManager $local_task_manager) {
    $this->localTaskManager = $local_task_manager;
  }

  /**
   * Reacts when configuration is saved.
   *
   * @param \Drupal\Core\Config\ConfigCrudEvent $event
   *   The event object.
   */
  public function onSave(ConfigCrudEvent $event) {
    $name = $event->getConfig()->getName();

    if (($name === 'lightning_api.settings' && $event->isChanged('entity_json')) || ($name === 'media.settings' && $event->isChanged('standalone_url'))) {
      $this->localTaskManager->clearCachedDefinitions();
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      ConfigEvents::SAVE => 'onSave',
    ];
  }

}
