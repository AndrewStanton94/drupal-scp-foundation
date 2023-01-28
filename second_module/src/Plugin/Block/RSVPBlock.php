<?php

/**
 * @file
 * Creates a block which displays the RSVPForm
 */

namespace Drupal\second_module\Form\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * The main RSVP block
 *
 * @Block(
 *   id = "rsvp_block",
 *   admin_label = @Translation("The RSVP Block")
 * )
 */
class RSVPBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    return [
      '#type' => 'markup',
      '#markup' => $this->t('My RSVP block list'),
    ];
  }
}
