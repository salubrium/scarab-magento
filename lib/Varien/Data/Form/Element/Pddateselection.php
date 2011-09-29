<?php
class Varien_Data_Form_Element_PdDateSelection extends Varien_Data_Form_Element_Date
{
    public function getElementHtml()
    {
        // define image url
        $this->setImage('/skin/adminhtml/default/default/images/grid-cal.gif');
        // define date format
        $this->setFormat('yyyy-MM-dd');

        return parent::getElementHtml();
    }
}