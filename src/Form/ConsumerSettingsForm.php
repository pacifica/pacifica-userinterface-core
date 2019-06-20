<?php

namespace Drupal\pacifica\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class ConsumerSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pacifica_consumer_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['pacifica.consumer.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('pacifica.consumer.settings');

    $form['consumer_enable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable RabbitMQ consumer.'),
      '#default_value' => $config->get('consumer_enable'),
    ];
    $form['consumer_timeout'] = [
      '#type' => 'select',
      '#options' => [
        5 => 5,
        15 => 15,
        30 => 30,
        45 => 45,
        60 => 60,
      ],
      '#title' => $this->t('Timeout'),
      '#default_value' => $config->get('consumer_timeout'),
      '#description' => $this->t('Set the timeout in seconds for how long the consumer should run each cron run.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->config('pacifica.consumer.settings')
      ->set('consumer_timeout', $form_state->getValue('consumer_timeout'))
      ->set('consumer_enable', $form_state->getValue('consumer_enable'))
      ->save();

    parent::submitForm($form, $form_state);

  }

}
