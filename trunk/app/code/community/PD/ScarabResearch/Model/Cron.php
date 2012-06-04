<?php


class PD_ScarabResearch_Model_Cron
{

        
        public function backup($cron = null)
        {
                function extract_numbers($string)
        {
                preg_match_all('/([\d]+)/', $string, $match);

                return $match[0];
        }
        try {

     
                 
 define('SAVE_FEED_LOCATION',Mage::getBaseDir().'/export/scarab.csv');
 $handle = fopen(SAVE_FEED_LOCATION, 'w');
 $heading = array('item','link','title','image','category','price','available','brand');
 $feed_line=implode(",", $heading)."\r\n";
 fwrite($handle, $feed_line); 

    //Loop through and print each products info
    $collection = Mage::getModel('catalog/product')
->getCollection()
->addAttributeToSelect('name')
->addAttributeToSelect('is_in_stock')
->addAttributeToFilter('visibility',4)
->addAttributeToFilter('status',1)
->addAttributeToSelect('is_saleable')
->addAttributeToSelect('manufacturer', $joinType=false)
->addAttributeToSelect('price');


foreach ($collection as $product) 
    {
$id = $product->getId();
$product = Mage::getModel('catalog/product')->load($id);
$categ = "";

# Get product's category collection object
$catCollection = $product->getCategoryCollection();
# export this collection to array so we could iterate on it's elements
$categs = $catCollection->exportToArray();
$categsToLinks = array();
# Get categories names
foreach($categs as $cat){
$categ .= $cat['path']."|";
}
$categ = substr($categ,0,-1);
$string = $categ;
$numbers_array = extract_numbers($string);
$array = array_unique($numbers_array);
arsort($array);
foreach ($array as $categoryId) {
 $category = Mage::getModel('catalog/category')->load($categoryId);
if ($categoryId < 3) {
$categ = str_replace($categoryId."/","",$categ);
$categ = str_replace($categoryId,"",$categ);
} else {
$catname = $category->getName();
$categ = str_replace($categoryId,$catname,$categ);
}
}

// get price
$finalprice = Mage::helper('tax')->getPrice($product, $product->getFinalPrice(), 2);
$stock = "false";       
if($product['is_in_stock'] == 1) {
$stock = "true";
}       

        
        
        $product_data = array();
        $product_data['item']=$product->getId();
      $product_data['product_url']=str_replace("/scarab.php/","/",$product->getProductUrl());
        $product_data['name']=$product->getName();
  $rconf = Mage::getStoreConfig('scarab_research/resize');
        $renabled = $rconf['resizeenabled'];
        $width = $rconf['resizewidth'];
        if ($renabled) {
        $product_data['image']=Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)."timthumb.php?src=".Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."catalog/product".$product->getImage()."&amp;w=".$width."&amp;zc=1";
        } else {
        $product_data['image']=Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."catalog/product".$product->getImage();
        }
   $product_data['kat']=str_replace("/"," > ", $categ);
        $product_data['price']=$finalprice;
        $product_data['status']=$stock;
        $product_data['manufacturer']=$product->getAttributeText('manufacturer');


 foreach($product_data as $k=>$val){
 $bad=array('"',"\r\n","\n","\r","\t");
 $good=array(""," "," "," ","");
 $product_data[$k] = '"'.str_replace($bad,$good,$val).'"';
 }

 $feed_line = implode(",", $product_data)."\r\n";

 
 fwrite($handle, $feed_line);
 fflush($handle);

 }
 
 
 
 //---------------------- WRITE THE FEED
 fclose($handle);



        } catch (Exception  $e) {
            Mage::logException($e);
        }

        return $this;
        }
   public function backupcron($cron = null)
   {
    file_get_contents(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'scarab.php');
   }
}
