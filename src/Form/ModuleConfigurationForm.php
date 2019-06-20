<?php

namespace Drupal\pacifica\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class ModuleConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pacifica_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'pacifica.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('pacifica.settings');
    $form['pacifica_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Pacifica App URL'),
      '#default_value' => $config->get('pacifica_url'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('pacifica.settings')
      ->set('pacifica_url', $values['pacifica_url'])
      ->save();
    parent::submitForm($form, $form_state);
  }

}
