<?php

class CRM_Orkestleden_BAO {
  public static function removeFromOrchestra($contactId) {
    try {
      // 1. het contact gaat uit de groep 'Orkestleden (huidige)'
      // 2. het contact wordt lid gemaakt van de groep 'Oud-leden'
      CRM_Ajocommon_GroupContact::delete($contactId, CRM_Ajocommon_Group::GROUP_ID_Orkestleden_huidige);
      CRM_Ajocommon_GroupContact::create($contactId, CRM_Ajocommon_Group::GROUP_ID_Orkestleden_oud);

      // 3. het contact wordt lid gemaakt van de groep 'Nieuwsbriefabonnees'
      CRM_Ajocommon_GroupContact::create($contactId, CRM_Ajocommon_Group::GROUP_ID_Nieuwsbriefabonnees);

      // 4. het lidmaatschap wordt beëindigd
      $membership = CRM_Ajocommon_MembershipContact::getCurrent($contactId, CRM_Ajocommon_Membership::TYPE_ORKESTLIDMAATSCHAP);
      if ($membership) {
        CRM_Ajocommon_MembershipContact::terminate($membership['id']);
      }

      // 5. zijn/haar ouders gaan uit de groep 'ouders'
      $contact = new CRM_Ajocommon_Contact($contactId);
      $parentsContactIds = $contact->getParentsContactIds();
      foreach ($parentsContactIds as $parentContactId) {
        // enkel indien er geen andere kinderen in het orkest zitten
        $numKidsInOrchestra = 0;
        $parent = new CRM_Ajocommon_Contact($parentContactId);
        $childrenContactIds = $parent->getChildrenContactIds();
        foreach ($childrenContactIds as $childrenContactId) {
          if (CRM_Ajocommon_GroupContact::isGroupContact($childrenContactId, CRM_Ajocommon_Group::GROUP_ID_Orkestleden_huidige)) {
            $numKidsInOrchestra++;
          }
        }
        if ($numKidsInOrchestra == 0) {
          // OK, this parent has no kids in the orchestra anymore
          CRM_Ajocommon_GroupContact::delete($parentContactId, CRM_Auditanten_Group::GROUP_Ouders);
        }

        // 6. zijn/haar ouders worden lid gemaakt van de groep 'Nieuwsbriefabonnees'
        CRM_Ajocommon_GroupContact::create($parentContactId, CRM_Ajocommon_Group::GROUP_ID_Nieuwsbriefabonnees);

        // 7. het contact verliest de WP-gebruikersstatus 'Orkestlid', maar blijft 'Abonnee'
        if ($contact->uf_id > 0) {
          $wpUser = new CRM_Ajocommon_User();
          $wpUser->loadById($contact->uf_id);
          $wpUser->removeRole('orkestlid');
        }
      }
    }
    catch (Exception $e) {
      CRM_Core_Session::setStatus($e->getTraceAsString(), $e->getMessage(), 'error');
    }

  }

  public static function isOrchestraMember($contactId): bool {
    if ($contactId) {
      return CRM_Ajocommon_GroupContact::isGroupContact($contactId, CRM_Ajocommon_Group::GROUP_ID_Orkestleden_huidige);
    }
    else {
      return FALSE;
    }
  }
}