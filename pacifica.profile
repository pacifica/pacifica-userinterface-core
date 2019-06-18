<?php

/**
 * @file
 * The Pacifica profile.
 */


/**
 * Implements hook_install_tasks().
 */
function pacifica_install_tasks() {
  $tasks = [];

  $tasks['pacifica_set_logo'] = [];

  return $tasks;
}

/**
 * Set the path to the logo file based on install directory.
 */
function pacifica_set_logo() {
  $pacifica_path = drupal_get_path('profile', 'pacifica');

  Drupal::configFactory()
    ->getEditable('system.theme.global')
    ->set('logo', [
      'path' => $pacifica_path . '/Pacifica.png',
      'url' => '',
      'use_default' => FALSE,
    ])
    ->save(TRUE);
}
