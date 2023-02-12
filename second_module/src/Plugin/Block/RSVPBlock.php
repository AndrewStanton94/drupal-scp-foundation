<?php

/**
 * @file
 * Creates a block which displays the RSVPForm
 */

namespace Drupal\second_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * The main RSVP block
 *
 * @Block(
 *   id = "rsvp_block",
 *   admin_label = @Translation("The RSVP Block"),
 * )
 */
class RSVPBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    // return [
    //  '#type' => 'markup',
    //  '#markup' => $this->t('My RSVP block list'),
    // ];
    return \Drupal::formBuilder()->getForm('Drupal\second_module\Form\RSVPForm');
  }

  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account) {
    $node = \Drupal::routeMatch()->getParameter('node');

    if ( !(is_null($node)) ) {
      $enabler = \Drupal::service('rsvplist.enabler');
      if ($enabler->isEnabled($node)) {
        return AccessResult::allowedIfHasPermission($account, 'view rsvplist');
      }
    }
    return AccessResult::forbidden();
  }
}
