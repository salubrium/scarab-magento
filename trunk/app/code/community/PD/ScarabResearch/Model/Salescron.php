<?php


class PD_ScarabResearch_Model_Salescron
{

	
	public function salesbackup($cron = null)
	{
		function extract_numbers($string)
	{
		preg_match_all('/([\d]+)/', $string, $match);

		return $match[0];
	}
        try {

 
		 
 define('SAVE_FEED_LOCATION',Mage::getBaseDir().'/export/scarabsales.csv');
 $handle = fopen(SAVE_FEED_LOCATION, 'w');
 $heading = array('order','item','customer','date','quantity','price');
 $feed_line=implode(",", $heading)."\r\n";
 fwrite($handle, $feed_line);

$_scconf = Mage::getStoreConfig('scarab_research/salesexport');
	$starttime = $_scconf['salestimefrom'];
	$endtime = $_scconf['salestimeto'];
if (strlen($starttime) > 5) {
$startw = " AND I.created_at > '$starttime'";
} else {
$startw = "";
}
if (strlen($endtime) > 5) {
$endw = " AND I.created_at < '$endtime'";
} else {
$endw = "";
}
	
	
$sql="SELECT I.product_id, I.created_at, I.qty_ordered, I.order_id, I.base_price_incl_tax, O.customer_id 
FROM sales_flat_order_item as I
JOIN sales_flat_order as O WHERE O.entity_id = I.order_id$startw$endw";

$data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($sql); 


foreach($data as $sql_row)
{

if ($sql_row['customer_id'] == "") {
$cid = str_replace(array(" ","-",":"),array("","",""),$sql_row['created_at']);
} else {
$cid = $sql_row['customer_id'];
}
	$product_data = array();
	$product_data['order']=$sql_row['order_id'];
	$product_data['item']=$sql_row['product_id'];
    $product_data['customer']=$cid;
	$product_data['date']=str_replace(" ","T",$sql_row['created_at'])."Z";
	$product_data['quantity']=$sql_row['qty_ordered'];
    $product_data['price']=$sql_row['base_price_incl_tax'];


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
   public function salesbackupcron($cron = null)
   {
    file_get_contents(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'scarabsales.php');
   }
}