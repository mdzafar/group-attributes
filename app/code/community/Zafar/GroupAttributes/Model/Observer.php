<?php
class Zafar_GroupAttributes_Model_Observer
{
  public function saveAttributeGroupName($observer)
  {
    $data = Mage::app()->getRequest()->getPost();
    if(array_key_exists('data',$data))
    {
      $setData = Mage::helper('core')->jsonDecode($data['data']);
      $attribute_set_groups = $setData['attribute_set_group_name'];
      foreach($attribute_set_groups as $attribute_set_group)
      {
        list($group_id, $store_id, $group_label_id, $group_label) = $attribute_set_group;
        $groupattributes = Mage::getModel('groupattributes/groups');
        if($group_label_id > 0){
        	$groupattributes->load($group_label_id);
        }
        $groupattributes->setAttributeGroupLabel($group_label);
        $groupattributes->setAttributeGroupId($group_id);
        $groupattributes->setStoreId($store_id);
        $groupattributes->save();
      }
    }
  }
}