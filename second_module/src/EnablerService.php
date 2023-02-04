<?php

/**
 * @file
 * Contains the RSVP enabler service
 */

namespace Drupal\second_module;

use Drupal\Core\Database\Connection;
use Drupal\node\Entity\Node;

class EnablerService {
  protected $database_connection;

  public function __construct(Connection $connection) {
    $this->database_connection = $connection;
  }

  /**
   * Determins if a node is RSVP enabled
   *
   * @param Node $node
   * @return bool
   *  Does the node have RSVP functionality
   */
  public function isEnabled(Node &$node) {
    if ($node->isNew()) {
      return FALSE;
    }
    try {
      $select = $this->database_connection->select('rsvplist_enabled', 're');
      $select->fields('re', ['nid']);
      $select->condition('nid', $node->id());
      $results = $select->execute();

      return !(empty($results->fetchCol());
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('Unable to determine RSVP settings at this time. Pleases try again')
      );
      return NULL;
    }
  }

  /**
   * Enables RSVP on the given node
   *
   * @param Node $node
   * @throws Exception
   */
  public function setEnabled(Node $node) {
    try {
      $insert = $this->database_connection->insert('rsvplist_enabled');
      $insert->fields(['nid']);
      $insert->values([$node->id()]);
      $insert->execute();
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('Unable to save RSVP at this time. Please try again')
      );
    }
  }

  /**
   * Delete RSVP enabled setting for a node
   *
   * @param Node $node
   */
  public function delEnabled(Node $node) {
    try {
      $delete = $this->database_connection->delete('rsvp_enabled');
      $delete->condition('nid', $node->id());
      $delete->execute();
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('Unable to remove RSVP from this node, please try again later')
      );
    }
  }
}
