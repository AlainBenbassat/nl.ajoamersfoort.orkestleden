<?php

require_once 'orkestleden.civix.php';

use CRM_Orkestleden_ExtensionUtil as E;

function orkestleden_civicrm_summaryActions(&$actions, $contactID) {
  if (CRM_Orkestleden_Contact::isOrchestraMember($contactID)) {
    // add menu
    $actions['otherActions']['schrap_orkestlid'] = [
      'title' => 'Orkestlid uitschrijven?',
      'weight' => 60,
      'ref' => 'orkestlid_uitschrijven',
      'key' => 'orkestlid_uitschrijven',
      'href' => CRM_Utils_System::url('civicrm/orkestlid-uitschrijven', 'reset=1&cid=' . $contactID),
    ];
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function orkestleden_civicrm_config(&$config): void {
  _orkestleden_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function orkestleden_civicrm_install(): void {
  _orkestleden_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function orkestleden_civicrm_enable(): void {
  _orkestleden_civix_civicrm_enable();
}
