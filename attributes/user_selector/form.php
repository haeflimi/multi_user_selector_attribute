<?php defined('C5_EXECUTE') or die("Access Denied.");
$page_selector = \Core::make('helper/form/page_selector');
$f = Loader::helper('form'); ?>

<fieldset class="ccm-attribute ccm-attribute-page-selector ccm-attribute-page-selector-<?php echo $key->getAttributeKeyID()?>">
    <?=$page_selector->selectPage('akID['.$key->getAttributeKeyID().'][value]', $pageID); ?>
</fieldset>