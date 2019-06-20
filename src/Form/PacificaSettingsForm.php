<?php

namespace Drupal\pacifica\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class PacificaSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pacifica_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['pacifica.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('pacifica.settings');

    $form['pacifica_host'] = [
      '#type' => 'url',
      '#title' => $this->t('URL'),
      '#default_value' => $config->get('pacifica_host'),
    ];
    $form['pacifica_user'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#default_value' => $config->get('pacifica_user'),
    ];
    $form['pacifica_pass'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->config('pacifica.settings')
      ->set('pacifica_host', $form_state->getValue('pacifica_host'))
      ->set('pacifica_user', $form_state->getValue('pacifica_user'))
      ->set('pacifica_pass', $form_state->getValue('pacifica_pass'))
      ->save();

    parent::submitForm($form, $form_state);

  }

}
