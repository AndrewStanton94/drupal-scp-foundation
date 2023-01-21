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
  public function submitForm( array &$form, FormStateInterface $form_state){
    $submitted_email = $form_state->getValue('email');
    $this->messenger()->addMessage(
      t("The form is working. You entered @entry", ['@entry' => $submitted_email])
    );
  }
}
