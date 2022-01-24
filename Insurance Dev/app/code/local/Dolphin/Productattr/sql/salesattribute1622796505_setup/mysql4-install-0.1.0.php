<?php
$installer = $this;
$installer->startSetup();

$installer->addAttribute("quote_item", "pre_order_date", array("type"=>"varchar"));
$installer->addAttribute("order_item", "pre_order_date", array("type"=>"varchar"));
$installer->endSetup();
	 