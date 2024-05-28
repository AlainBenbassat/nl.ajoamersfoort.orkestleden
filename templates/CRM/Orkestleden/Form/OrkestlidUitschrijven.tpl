
<div class="help">
  <h3>Gevolgen:</h3>
  <ol>
    <li>het contact gaat uit de groep 'Orkestleden (huidige)'</li>
    <li>het contact wordt lid gemaakt van de groep 'Oud-leden'</li>
    <li>het contact wordt lid gemaakt van de groep 'Nieuwsbriefabonnees'</li>
    <li>het lidmaatschap wordt beëindigd</li>
    <li>zijn/haar ouders gaan uit de groep 'ouders'</li>
    <li>zijn/haar ouders worden lid gemaakt van de groep 'Nieuwsbriefabonnees'</li>
    <li>het contact verliest de WP-gebruikersstatus 'Orkestlid', maar blijft 'Abonnee'</li>
  </ol>
</div>

{foreach from=$elementNames item=elementName}
  <div class="crm-section">
    <div class="label">{$form.$elementName.label}</div>
    <div class="content">{$form.$elementName.html}</div>
    <div class="clear"></div>
  </div>
{/foreach}


{* FOOTER *}
<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
