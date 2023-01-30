<?php

/**
 * @file Form to manage RSVP settings
 */

namespace Drupal\second_module\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class RSVPSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId () {
    return 'rsvplist_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'rsvplist.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // What types of pages do we have?
    $types = node_type_get_names();
    $config = $this->config('rsvplist.settings');
    // Each type of page is a checkbox, their state comes from the config
    $form['rsvplist_types'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Content types to enable RSVP collection for'),
      '#default_value' => $config->get('allowed_types'),
      '#options' => $types,
      '#description' => $this->t('Allows pages of these types to collect RSVPs'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Remove empty fields
    $selected_allowed_types = array_filter($form_state->getValue('rsvplist_types'));
    sort($selected_allowed_types);

    $this->config('rsvplist.settings')
       ->set('allowed_types', $selected_allowed_types)
       ->save();

    parent::submitForm($form, $form_state);
  }
}
