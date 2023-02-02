<?php

/**
 * @file Provides admins with a list of all the RSVP signups
 */

namespace Drupal\second_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class ReportController extends ControllerBase {

  /**
   * Returns the RSVPs for all nodes
   * An associative array
   * Each row contains the username, node title and email
   *
   * @return array|null
   */
  protected function load() {
    try {
      $database = \Drupal::database();
      $select_query = $database->select('rsvplist', 'r');

      // Join the user table to get the username
      $select_query->join('users_field_data', 'u', 'r.uid == u.uid');

      // Join the node table to get the event name
      $select_query->join('node_field_data', 'n', 'r.nid = n.nid');

      $select_query->addField('u', 'name', 'username');
      $select_query->addField('n', 'title');
      $select_query->addField('r', 'mail');

      $entries = $select_query->execute()->fetchAll(\PDO::FETCH_ASSOC);

      return $entries;
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addStatus(t('Unable to access the database. Please try again later'));
      return NULL;
    }
  }

  /**
   * Creates the RSVP report page
   *
   * @return array
   * Render array for the RSVP report
   */
  public function report() {
    $content = [];

    $content['message'] = [
      '#markup' => t('Below is a list of Event RSVPs with username, email and the eventname'),
    ];

    $headers = [
      t('Username'),
      t('Event'),
      t('Email'),
    ];

    $table_rows = $this->load();

    $content['table'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $table_rows,
      '#empty' => t('No entries available'),
    ];

    // Prevent caching
    $content['#cache']['max-age'] = 0;

    return $content;
  }
}
