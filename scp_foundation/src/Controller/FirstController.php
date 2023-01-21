<?php

/*
 * @file
 * Generates the markup
 * It's linked to Drupal scp_foundation.routing.yml
 */

namespace Drupal\scp_foundation\Controller;

use Drupal\Core\Controller\ControllerBase;

class FirstController extends ControllerBase {

  public function simpleContent() {
    return [
      '#type' => 'markup',
      '#markup' => t('Hello Foundation'),
    ];
  }

  public function variableContent($name_1, $name_2) {
    return [
      '#type' => 'markup',
      '#markup' => t(
        'Greetings @researcher, you have been asigned to @scp',
        ['@researcher' => $name_1, '@scp' => $name_2]
      ),
    ];
  }
}
