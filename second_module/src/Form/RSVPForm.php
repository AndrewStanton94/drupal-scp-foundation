<?php

/**
 * @file
 * A form to collect an email address for RSVP details
 */

namespace Drupal\second_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class RSVPForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'rsvplist_email_form';
  }

  /**
  * {@inheritdoc}
  */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Returns a render array
    // This includes a text field, submit button and a hidden field

    $node = \Drupal::routeMatch()->getParameter('node');
    if ( !(is_null($node)) ) {
      $nid = $node->id();
    } else {
      $nid = 0;
    }

    $form['email'] = [
      '#type' => 'textfield',
      '#title' => t('Email address'),
      '#size' => 25,
      '#description' => t('We will send your updates here'),
      '#required' => TRUE,
    ];
    
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('RSVP'),
    ];
    
    $form['nid'] = [
      '#type' => 'hidden',
      '#value' => $nid,
    ];
    
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $value = $form_state->getValue('email');
    if ( !(\Drupal::service('email.validator')->isValid($value)) ) {
      $form_state->setErrorByName('email',
        $this->t('Nope: %mail does not look like a valid email', ['%mail' => $value]));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm( array &$form, FormStateInterface $form_state){
    // $submitted_email = $form_state->getValue('email');
    // $this->messenger()->addMessage(
      // t("The form is working. You entered @entry", ['@entry' => $submitted_email])
    // );
    try {
      // Get the variables to save
      // Current user info
      $uid = \Drupal::currentUser()->id();

      // Current form submission info
      $nid = $form_state->getValue('nid');
      $email = $form_state->getValue('email');
      // Current time info
      $current_time = \Drupal::time()->getCurrentTime();

      // Saving the data
      $query = \Drupal::database->insert('rsvplist');
      $query->fields([
        'uid',
        'nid',
        'mail',
        'created',
      ]);
      $query->values([
        $uid,
        $nid,
        $email,
        $current_time,
      ]);
      $query->execute();

      // User confirmation
      \Drupal::messenger()->addMessage(t('RSVP saved'));
    }
      catch (\Exception $e) {
        \Drupal::messenger()->addError(t('RSVP didn\'t save. Please try again'));
    }
  }
}
