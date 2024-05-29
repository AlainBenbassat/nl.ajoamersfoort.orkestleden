<?php

use CRM_Orkestleden_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Orkestleden_Form_OrkestlidUitschrijven extends CRM_Core_Form {

  /**
   * @throws \CRM_Core_Exception
   */
  public function buildQuickForm(): void {
    $this->setTitle('Orkestlid uitschrijven?');

    $contactId = $this->getContactIdFromQueryParam();

    $this->addFormElements($contactId);
    $this->addFormButtons();

    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  public function postProcess(): void {
    try {
      $values = $this->exportValues();
      $contactId = $values['contact_id'];

      if ($values['remove_member'] == '1') {
        CRM_Orkestleden_BAO::removeFromOrchestra($contactId);
        CRM_Core_Session::setStatus('Het contact is aangepast', '', 'success');
      }

      CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/contact/view', "reset=1&cid=$contactId"));

      parent::postProcess();
    }
    catch (Exception $e) {
      CRM_Core_Session::setStatus($e->getMessage(),'Fout', 'error');
    }
  }

  private function addFormElements($contactId) {
    $candidateName = \Civi\Api4\Contact::get(FALSE)
      ->addSelect('display_name')
      ->addWhere('id', '=', $contactId)
      ->execute()
      ->single()['display_name'];

    $this->addYesNo('remove_member', "$candidateName uitschrijven als orkestlid?", TRUE, TRUE);

    $this->add('hidden', 'contact_id', $contactId);
  }

  private function addFormButtons() {
    $this->addButtons([
      [
        'type' => 'submit',
        'name' => E::ts('Submit'),
        'isDefault' => TRUE,
      ],
    ]);
  }

  private function getContactIdFromQueryParam() {
    return CRM_Utils_Request::retrieve('cid', 'Integer', $this, TRUE);
  }

  private function getRenderableElementNames(): array {
    $elementNames = [];
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

}
