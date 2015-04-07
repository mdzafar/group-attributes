<?php
/**
 * Product description block
 *
 * @category   Mage
 * @package    Zafar_GroupAttributes
 * @author     Zafar <zafar.asn@gmail.com>
 */
class Zafar_GroupAttributes_Block_Catalog_Product_View_Attributes extends Mage_Catalog_Block_Product_View_Attributes
{
    /**
     * $excludeAttr is optional array of attribute codes to
     * exclude them from additional data array
     *
     * @param array $excludeAttr
     * @return array
     */
    public function getAdditionalData(array $excludeAttr = array())
    {
        $data = array();
        $product = $this->getProduct();
        $attributes = $product->getAttributes();
        $group_ids = array();
        $group_labels = array();
        foreach ($attributes as $attribute) {
//            if ($attribute->getIsVisibleOnFront() && $attribute->getIsUserDefined() && !in_array($attribute->getAttributeCode(), $excludeAttr)) {
            if ($attribute->getIsVisibleOnFront() && !in_array($attribute->getAttributeCode(), $excludeAttr)) {
                $value = $attribute->getFrontend()->getValue($product);

                if (!$product->hasData($attribute->getAttributeCode())) {
                	continue;
                    $value = Mage::helper('catalog')->__('N/A');
                } elseif ((string)$value == '') {
                    $value = Mage::helper('catalog')->__('No');
                } elseif ($attribute->getFrontendInput() == 'price' && is_string($value)) {
                    $value = Mage::app()->getStore()->convertPrice($value, true);
                }
                if (is_string($value) && strlen($value)) {
                    $arributes_data = $attribute->getData();
                    if(!in_array($arributes_data['attribute_set_info'][$product->getAttributeSetId()]['group_id'],$group_ids)){
                        $group_ids[] = $arributes_data['attribute_set_info'][$product->getAttributeSetId()]['group_id'];
                        $group_label = Mage::getResourceModel('groupattributes/groups_collection')
                                    ->addFieldToFilter('attribute_group_id', $arributes_data['attribute_set_info'][$product->getAttributeSetId()]['group_id'])
                                    ->addFieldToFilter('store_id', Mage::app()->getStore()->getId())
                                    ->getData();
						if(count($group_label)>0){
                        	$group_labels[$arributes_data['attribute_set_info'][$product->getAttributeSetId()]['group_id']] = $group_label[0]['attribute_group_label'];
						}
                    }
                    $group_id_key = array_search($arributes_data['attribute_set_info'][$product->getAttributeSetId()]['group_id'], $group_ids);
                    $data[$group_id_key]['title'] = $group_labels[$arributes_data['attribute_set_info'][$product->getAttributeSetId()]['group_id']];
                    $data[$group_id_key]['attributes'][] = array(
                                                                    'label'=> $attribute->getStoreLabel(),
                                                                    'value'=> $value,
                                                                    'code'=> $attribute->getAttributeCode(),
                                                                );
                    unset($arributes_data);
                }
            }
        }
        return $data;
    }
	public function getAdditionalDataByGroup($product, array $excludeAttr = array())
    {
        $data = array();
		$group_ids = array();
        $group_labels = array();
        //$product = $this->getProduct();
        $attributes = $product -> getAttributes();
        foreach ($attributes as $attribute) {

            //            if ($attribute->getIsVisibleOnFront() && $attribute->getIsUserDefined() &&
            // !in_array($attribute->getAttributeCode(), $excludeAttr)) {
            if ($attribute -> getIsVisibleOnFront() && !in_array($attribute -> getAttributeCode(), $excludeAttr)) {
                $value = $attribute -> getFrontend() -> getValue($product);

                if (!$product -> hasData($attribute -> getAttributeCode())) {
                    $value = Mage::helper('catalog') -> __('N/A');
                } elseif ((string)$value == '') {
                    $value = Mage::helper('catalog') -> __('No');
                } elseif ($attribute -> getFrontendInput() == 'price' && is_string($value)) {
                    $value = Mage::app() -> getStore() -> convertPrice($value, true);
                }

               /* if (is_string($value) && strlen($value)) {
                    $data[$attribute -> getAttributeGroupId()][] = array(
                        'label' => $attribute -> getStoreLabel(),
                        'value' => $value,
                        'code' => $attribute -> getAttributeCode(),
                    );
                }*/
				
				
				if (is_string($value) && strlen($value)) {
                    $arributes_data = $attribute->getData();
                    if(!in_array($arributes_data['attribute_set_info'][$product->getAttributeSetId()]['group_id'],$group_ids)){
                        $group_ids[] = $arributes_data['attribute_set_info'][$product->getAttributeSetId()]['group_id'];
                        $group_label = Mage::getResourceModel('groupattributes/groups_collection')
                                    ->addFieldToFilter('attribute_group_id', $arributes_data['attribute_set_info'][$product->getAttributeSetId()]['group_id'])
                                    ->addFieldToFilter('store_id', Mage::app()->getStore()->getId())
                                    ->getData();
						if(isset($group_label[0]['attribute_group_label'])){
							$group_labels[$arributes_data['attribute_set_info'][$product->getAttributeSetId()]['group_id']] = $group_label[0]['attribute_group_label'];	
						}                        
                    }
                    $group_id_key = array_search($arributes_data['attribute_set_info'][$product->getAttributeSetId()]['group_id'], $group_ids);
                    $data[$group_id_key]['title'] = $group_labels[$arributes_data['attribute_set_info'][$product->getAttributeSetId()]['group_id']];
                    $data[$group_id_key]['attributes'][] = array(
                                                                    'label'=> $attribute->getStoreLabel(),
                                                                    'value'=> $value,
                                                                    'code'=> $attribute->getAttributeCode(),
                                                                );
                    unset($arributes_data);
                }
				
				
            }
        }

        return $data;
    }
}