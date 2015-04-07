<?php
class Zafar_GroupAttributes_Block_Adminhtml_Catalog_Product_Attribute_Set_Main_Formset extends
Mage_Adminhtml_Block_Catalog_Product_Attribute_Set_Main_Formset
{

    /**
     * Prepares attribute set form
     *
     */
    protected function _prepareForm()
    {
        parent::_prepareForm();
        if($this->getRequest()->getParam('id')) {
            $form = $this->getForm();
            $resource = Mage::getSingleton('core/resource');
            $connection = $resource->getConnection('groupattributes_read');
            $sql = $connection->select()
                              ->distinct()
                              ->from(array('eag' => $resource->getTableName('eav_attribute_group')), array('group_name' => 'eag.attribute_group_name'))
                              ->join(array('eea' => $resource->getTableName('eav_entity_attribute')),'eag.attribute_group_id = eea.attribute_group_id AND eag.attribute_set_id = eea.attribute_set_id',array('group_id' => 'eea.attribute_group_id'))
                              ->join(array('cea' => $resource->getTableName('catalog_eav_attribute')), 'eea.attribute_id = cea.attribute_id', array())
                              ->where("cea.is_visible_on_front = ?",1)
                              ->where("eea.entity_type_id = ?",4)
                              ->where("eea.attribute_set_id = ?",$this->getRequest()->getParam('id'));
            $query = $connection->query($sql);
            $stores = Mage::app()->getStores();
            while($group = $query->fetch()){
                $fieldset = $form->addFieldset('set_group_name_'.$group['group_id'], array('legend'=> Mage::helper('catalog')->__('Edit frontend label for group %s',$group['group_name'])));
                foreach($stores as $store){
                    $group_name_label = $group['group_name'];
                    $website = Mage::getModel('core/website')->load($store->getWebsiteId());
                    $group_label = Mage::getResourceModel('groupattributes/groups_collection')
                                    ->addFieldToFilter('attribute_group_id', $group['group_id'])
                                    ->addFieldToFilter('store_id', $store->getStoreId())
                                    ->getData();
                    if(!empty($group_label[0]['attribute_group_label'])){
                        $group_name_label = $group_label[0]['attribute_group_label'];
                    }
                    $fieldset->addField('attribute-set-group-name_'.$group['group_id'].'_'.$store->getStoreId().'_'.$group_label[0]['attribute_group_label_id'], 'text',
                                    array(
                                        'label' => Mage::helper('catalog')->__('%s - %s',$store->getName(),$website->getName()),
                                        'name' => 'attribute_set_group_name['.$store->getStoreId().']'.'['.$group['group_id'].']',
                                        'value' => $group_name_label,
                                        'class' => 'set-group-name',
                                    ));
                }
            }
        }
    }
}