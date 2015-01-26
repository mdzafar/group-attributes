<?php
class Zafar_GroupAttributes_Model_Mysql4_Groups extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Initialize resource model
     */
    protected function _construct()
    {    
        $this->_init('groupattributes/groups', 'attribute_group_label_id');
    }
}