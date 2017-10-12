<?php
/**
*
* Order detail view
*
* @package	VirtueMart
* @subpackage Orders
* @author Oscar van Eijk, Valerie Isaksen
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: details.php 8832 2015-04-15 16:05:49Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
JHtml::stylesheet('vmpanels.css', JURI::root().'components/com_virtuemart/assets/css/');
if($this->print){
	?>

		<body onload="javascript:print();">
		<div class="vm-orders-vendor-image"><img src="<?php  echo JURI::root() . $this-> vendor->images[0]->file_url ?>"></div>
		<h2><?php  echo $this->vendor->vendor_store_name; ?></h2>
		<?php  echo $this->vendor->vendor_name .' - '.$this->vendor->vendor_phone ?>
		<h1><?php echo vmText::_('COM_VIRTUEMART_ACC_ORDER_INFO'); ?></h1>
		<div class="spaceStyle vm-orders-order print">
		<?php
		echo $this->loadTemplate('order');
		?>
		</div>

		<div class="spaceStyle vm-orders-items print">
		<?php
		echo $this->loadTemplate('items');
		?>
		</div>
		<?php if(!class_exists('VirtuemartViewInvoice')) require_once(VMPATH_SITE .DS. 'views'.DS.'invoice'.DS.'view.html.php');
		echo VirtuemartViewInvoice::replaceVendorFields($this->vendor->vendor_letter_footer_html, $this->vendor); ?>
		</body>
		<?php
} else {

	?>
<div class="vm-wrap">
<link href="components/com_virtuemart/views/workorders/css/print.css" rel="stylesheet" type="text/css" />

		
	<div class="trilly-print-page" media="print">

		<div class="print-header">
			<div class="print-header-left">
				<div class="trilly-logo">
				<img src="https://trilliumorderdesk.com/templates/trilly/images/Trillium-Logo.png" alt="Trillium Sales Group Logo">
				</div>
				<h1>Work Order</h1>
			</div><!-- end print-header-left-->
					<div class="print-header-right">
				<div class="header-details">
					<span class="header-topic">Sales Order#:</span>
					<span class="header-data"><strong><?php echo $this->orderdetails['details']['BT']->order_number; ?></strong></span>
					<span class="header-topic">Work Order#:</span>
					<span class="header-data"><?php echo $this->orderdetails['details']['BT']->virtuemart_order_id; ?></span>
					<span class="header-topic">Order Date:</span>
					<span class="header-data"><?php echo vmJsApi::date($this->orderdetails['details']['BT']->created_on, 'LC4', true); ?></span>
					<span class="header-topic">Verified Date:</span>
					<span class="header-data"><?php echo vmJsApi::date($this->orderdetails['details']['BT']->modified_on, 'LC4', true); ?><!-- *** Sameh please add VM Order Status = Verified Date here please --></span>
					<span class="header-topic">Shipping Method:</span>
					<span class="header-data"><?php
	    echo $this->shipment_name;
	    ?><!-- *** Sameh please add VM Shipping Method here please --></span>
					<span class="header-topic">Payment Method:</span>
					<span class="header-data"><?php echo $this->payment_name; ?><!-- *** Sameh please add VM Payment Method here please --></span>
				</div>
			</div><!-- end print-header-right-->
		
		</div><!-- end print-header-->
		
		<div class="print-details">
		
			<div class="print-details-left">
			
				<h6>Customer Details</h6>
					<span class="details-left-col"><?php echo $this->orderdetails['details']['BT']->company; ?><!-- *** Sameh please add Company Name here please --></span>
					<span class="details-right-col"><?php echo $this->orderdetails['details']['BT']->address_1; ?><!-- *** Sameh please add Customer Billing Address here please --></span>
					<span class="details-left-col"><?php echo $this->orderdetails['details']['BT']->first_name. ' ' .$this->orderdetails['details']['BT']->last_name; ?><!-- *** Sameh please add Customer Full Name here please --></span>
					<span class="details-right-col"><?php echo $this->orderdetails['details']['BT']->city. ' ' .$this->userfields['fields']['virtuemart_state_id']['value'];?></span>
					<span class="details-left-col"><?php echo $this->orderdetails['details']['BT']->phone_1; ?><!-- *** Sameh please add Formatted Phone Number here please --></span>
					<span class="details-right-col"><?php echo $this->userfields['fields']['virtuemart_country_id']['value']; ?><!-- *** Sameh please add Country here please --></span>
					<span class="details-right-col"><?php echo $this->orderdetails['details']['BT']->email; ?><!-- *** Sameh please add Customer Email here please --></span>
					<span class="details-left-col"><?php echo $this->orderdetails['details']['BT']->zip; ?><!-- *** Sameh please add ZIP/Postal code here please --></span>
					
			</div><!-- end print-details-left-->
			
			<div class="print-details-right">
			
				<h6>Shipping Address</h6>
					<span class="shipping-left-col"><?php echo $this->shipmentfields['fields']['address_1']['value'] ;?><!-- *** Sameh please add Customer Shipping Address here please --></span>
					<span class="shipping-left-col"><?php echo $this->shipmentfields['fields']['city']['value']. ' ' .$this->shipmentfields['fields']['virtuemart_state_id']['value'];?><!-- *** Sameh please add Shipping City & Prov/State here please --></span>
					<span class="shipping-left-col"><?php echo $this->shipmentfields['fields']['virtuemart_country_id']['value']; ?><!-- *** Sameh please add Shipping Country here please --></span>
					<span class="shipping-left-col"><?php echo $this->shipmentfields['fields']['zip']['value']; ?><!-- *** Sameh please add SHipping ZIP/Postal code here please --></span>
					
			</div><!-- end print-details-right-->
			
		</div><!-- end print-details-->
			
		<div class="order-items-table">
		
			<h6>Order Items</h6>
			<table class="work-order-items">
			
				<tr class="work-order-table-header">
					<th class="image-column">Image</th>
					<th class="qty-column">Qty.</th>
					<th class="sku-column">SKU</th>
					<th class="description-column">Description</th>
				</tr>
				
				
			<?php
	$productModel = VmModel::getModel('product');
	$mediaModel = VmModel::getModel('Media');
	
	foreach($this->orderdetails['items'] as $item) {
		$qtt = $item->product_quantity ;
$productz = $productModel->getProduct($item->virtuemart_product_id);
//print_r($productz);
$media = $mediaModel->getFiles(true,true,$productModel->_id);
$index = 0;

/* Search the images of the product */

?>
		<tr valign="top">
			
			<td class="image-column" >
		<img src="<?php echo $media[0]->getUrl();?>" alt=""/>
			</td>
					
			<td class="qty-column" >
				<?php
	//print_r($item);

				echo $qtt; ?>
			</td>
			<td class="sku-column">
				<?php echo $item->order_item_sku; ?>
			</td>
	
			<td class="description-column" >
					<div><?php echo $item->order_item_name; ?></div>
			</td>
		
		</tr>

<?php
	}	

?>	
			</table>
			
		</div><!-- end order-items-table-->
		
		<div class="work-order-footer">
		
		<div class="work-order-footer-left">
			<h6>Production Images</h6>
			
				<div class="production-image-wrapper">
				<?php
				$added=trim($this->orderdetails['details']['BT']->mediafiles, ","); 
				$added=str_replace(",,",",",$added);
				$addedarray = explode(",", $added);
				$aded=implode("','",$addedarray);
				$db = JFactory::getDBO();// Joomla database object
				$user = JFactory::getUser();
				$userid=$user->id;
				$sql="select * from #__order_media where id IN ('$aded') AND use_in_wrkrdr='1'";
				$db->setQuery($sql);
				$images = $db->loadObjectList();
				foreach($images as $image){
				$filename=JURI::root().'dz/uploads/'.$image->file_name;
$ext = pathinfo($filename, PATHINFO_EXTENSION);
$ext=strtolower($ext);
if($ext=="png" || $ext=="jpg" || $ext=="gif" ){

?>
<a href="<?php echo $filename; ?>" target="_blank" download ><img src="<?php echo $filename; ?>" width="100%"/></a>
<?php

}else{
?>
<a href="<?php echo $filename; ?>" target="_blank" download ><img src="//cdn2.iconfinder.com/data/icons/office-extras/512/Text_Picture_Document-512.png" width="100%"/></a>
<?php
}
}
	?>
				</div><!-- production-image-wrapper-->
				
		</div><!-- work-order-footer-left-->
		
		<div class="work-order-footer-right">
			<h6>Ad Copy Instructions</h6>
			
				<div class="ad-copy-wrapper">
				<?php echo $this->orderdetails['details']['BT']->Customer_Notes;?>
				</div><!-- ad-copy-wrapper-->
			
		</div><!-- work-order-footer-right-->
		
		</div><!-- work-order-footer-->
		
	</div><!-- end trilly-print-page -->
</div>	
	<?php
}

?>






