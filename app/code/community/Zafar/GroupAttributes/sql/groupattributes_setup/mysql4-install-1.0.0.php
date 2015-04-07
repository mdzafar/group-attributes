<?php
$installer = $this;
$installer->startSetup();
$installer->run("
	CREATE TABLE `{$this->getTable('eav_frontend_attribute_group_label')}` (
  	`attribute_group_label_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  	`attribute_group_label` varchar(255) DEFAULT NULL,
  	`attribute_group_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  	`store_id` smallint(5) unsigned NOT NULL DEFAULT '0',
	  PRIMARY KEY (`attribute_group_label_id`),
  	KEY `FK_eav_frontend_attribute_group_label_store` (`store_id`),
  	KEY `FK_eav_frontend_attribute_group_label_group` (`attribute_group_id`),
  	CONSTRAINT `FK_eav_frontend_attribute_group_label_group` FOREIGN KEY (`attribute_group_id`) REFERENCES `{$this->getTable('eav_attribute_group')}` (`attribute_group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  	CONSTRAINT `FK_eav_frontend_attribute_group_label_store` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core_store')}`   (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->endSetup();