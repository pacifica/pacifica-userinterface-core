<?php

namespace Drupal\pacifica\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class RabbitmqSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pacifica_rabbitmq_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['pacifica.rabbitmq.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('pacifica.rabbitmq.settings');

    $form['rabbitmq_host'] = [
      '#type' => 'url',
      '#title' => $this->t('URL'),
      '#default_value' => $config->get('rabbitmq_host'),
    ];
    $form['rabbitmq_port'] = [
      '#type' => 'number',
      '#title' => $this->t('Port'),
      '#default_value' => $config->get('rabbitmq_port'),
    ];
    $form['rabbitmq_user'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#default_value' => $config->get('rabbitmq_user'),
    ];
    $form['rabbitmq_pass'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#default_value' => $config->get('rabbitmq_pass'),
    ];
    $form['rabbitmq_queue'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Queue name'),
      '#default_value' => $config->get('rabbitmq_queue'),
      '#description' => $this->t('Enter the name of the RabbitMP queue that the DataHub module should listen to.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->config('pacifica.rabbitmq.settings')
      ->set('rabbitmq_host', $form_state->getValue('rabbitmq_host'))
      ->set('rabbitmq_port', $form_state->getValue('rabbitmq_port'))
      ->set('rabbitmq_user', $form_state->getValue('rabbitmq_user'))
      ->set('rabbitmq_pass', $form_state->getValue('rabbitmq_pass'))
      ->set('rabbitmq_queue', $form_state->getValue('rabbitmq_queue'))
      ->save();

    parent::submitForm($form, $form_state);

  }

}
