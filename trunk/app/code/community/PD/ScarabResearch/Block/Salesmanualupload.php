<?php 


class PD_ScarabResearch_Block_Salesmanualupload extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
    	
        $this->setElement($element);
        $baseurl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('scalable')
                    ->setLabel('Export Now!')
                    ->setOnClick("window.open('".$baseurl."scarabsales.php','window','width=400,height=200')")
                    ->toHtml();
        
        return $html;
    }
}

?>
