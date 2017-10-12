<?php
/**
 * Display form details
 *
 * @package	VirtueMart
 * @subpackage Orders
 * @author Oscar van Eijk
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: order.php 9239 2016-06-22 21:50:44Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

AdminUIHelper::startAdminArea($this);
AdminUIHelper::imitateTabs('start','COM_VIRTUEMART_ORDER_PRINT_PO_LBL');

// Get the plugins
JPluginHelper::importPlugin('vmshopper');
JPluginHelper::importPlugin('vmshipment');
JPluginHelper::importPlugin('vmpayment');

vmJsApi::addJScript( 'orderedit',"
		jQuery( function($) {

			$('.orderedit').hide();
			$('.ordereditI').show();
			$('.orderedit').css('backgroundColor', 'lightgray');

			jQuery('.updateOrderItemStatus').click(function() {
				document.orderItemForm.task.value = 'updateOrderItemStatus';
				document.orderItemForm.submit();
				return false
			});

			jQuery('select#virtuemart_paymentmethod_id').change(function(){
				jQuery('span#delete_old_payment').show();
				jQuery('input#delete_old_payment').attr('checked','checked');
			});

		});

		function enableEdit(e)
		{
			jQuery('.orderedit').each( function()
			{
				var d = jQuery(this).css('visibility')=='visible';
				jQuery(this).toggle();
				jQuery('.orderedit').css('backgroundColor', d ? 'white' : 'lightgray');
				jQuery('.orderedit').css('color', d ? 'blue' : 'black');
			});
			jQuery('.ordereditI').each( function()
			{
				jQuery(this).toggle();
			});
			e.preventDefault();
		};

		function addNewLine(e,i) {

			var row = jQuery('#itemTable').find('#lItemRow').html();
			var needle = 'item_id['+i+']';
			//var needle = new RegExp('item_id['+i+']','igm');
			while (row.indexOf(needle) !== -1){
				row = row.replace(needle,'item_id[0]');
			}

			//alert(needle);
			jQuery('#itemTable').find('#lItemRow').after('<tr>'+row+'</tr>');
			e.preventDefault();
		};

		function cancelEdit(e) {
			jQuery('#orderItemForm').each(function(){
				this.reset();
			});
			jQuery('.selectItemStatusCode')
				.find('option:selected').prop('selected', true)
				.end().trigger('liszt:updated');
			jQuery('.orderedit').hide();
			jQuery('.ordereditI').show();
			e.preventDefault();
		}

		function resetOrderHead(e) {
			jQuery('#orderForm').each(function(){
				this.reset();
			});
			jQuery('select#virtuemart_paymentmethod_id')
				.find('option:selected').prop('selected', true)
				.end().trigger('liszt:updated');
			jQuery('select#virtuemart_shipmentmethod_id')
				.find('option:selected').prop('selected', true)
				.end().trigger('liszt:updated');
			e.preventDefault();
		}

		");

$j = "
jQuery('.show_element').click(function() {
  jQuery('.element-hidden').toggle();
  jQuery('select').trigger('chosen:updated');
  return false;
});
jQuery('.updateOrderItemStatus').click(function() {
	document.orderItemForm.task.value = 'updateOrderItemStatus';
	document.orderItemForm.submit();
	return false;
});
jQuery('.updateOrder').click(function() {
	document.orderForm.submit();
	return false;
});
jQuery('.createOrder').click(function() {
	document.orderForm.task.value = 'CreateOrderHead';
	document.orderForm.submit();
	return false;
});
jQuery('.newOrderItem').click(function() {
	document.orderItemForm.task.value = 'newOrderItem';
	document.orderItemForm.submit();
	return false;
});
jQuery('.orderStatFormSubmit').click(function() {
	//document.orderStatForm.task.value = 'updateOrderItemStatus';
	document.orderStatForm.submit();

	return false;
});

function confirmation(destnUrl) {
	var answer = confirm('".addslashes( vmText::_('COM_VIRTUEMART_ORDER_DELETE_ITEM_JS') )."');
if (answer) {
	window.location = destnUrl;
	}
}

var editingItem = 0;
";
vmJsApi::addJScript('ordergui',$j);

?>
<style>
textarea#BT_customer_note_field
{
width: 98%;
min-height: 100px;
}
div.added
{
width:100% !important;
}
div.added img
{
max-width: 6%;
margin-right: 2%;
min-height: 40px;
}
div.order-media
{
max-height: 400px;
overflow-y: auto;
overflow-x: hidden;
margin-bottom: 30px;
}
fieldset textarea
{
width: 98%;
}
#admin-ui-tabs div.tabs
{
padding: 10px 1px 20px;
overflow: visible;
position: relative;
top: -30px;
}
#admin-ui-tabs ul#tabs li.current span
{
display:none !important;
}
#admin-ui-tabs ul#tabs
{
background:transparent;
}
#admin-ui-tabs ul#tabs li
{
background:transparent;
height:0px;
}
div.quicklinks a
{
background:url(/templates/trilly/images/Dark-Grey-Chevron-Down.png) no-repeat;
height:40px;
line-height:40px;
display:inline-block;
background-position:100% 50%;
padding-right:20px;
font-size:16px;
margin-right:20px;
}
div.quicklinks a:hover
{
text-decoration:none;
}
.chzn-container-single .chzn-drop, .chzn-container, .chzn-drop
{
width:240px !important;
}

.wrkrdry{
	color:green;
	cursor: pointer;
	cursor: hand; 
}

.wrkrdrn{
	color:red;
	cursor: pointer;
	cursor: hand; 
}
.hide{
	display:none !important;
}
table.production-table
{
width:100%;
}
table.production-table td, table.production-table th
{
padding:5px;
}
table.production-table td
{
vertical-align:top;
}
.icon-column
{
width:60px;
}
td.icon-column img
{
max-height:50px;
width:auto;
}
td.title-column i.wrkrdrn
{
color: lightgrey !important;
}
table.production-table td.title-column:hover i
{
color: red !important;
}
td.production-column i.wrkrdrn
{
color: lightgrey !important;
}
td.production-column:hover i.wrkrdrn
{
color: green !important;
}
textarea#BT_Admin_Notes_field
{
width:98%;
min-height:200px;
}
</style>

<link rel="stylesheet" type="text/css" href="<?php echo  JURI::root();?>dz/css/dropzone.css" />
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
<script type="text/javascript" src="<?php echo  JURI::root() ;?>dz/js/dropzone.js"></script>
<script type="text/javascript" src="<?php echo  JURI::root() ;?>dz/Nibbler.js"></script>
<script type="text/javascript">
    
	base32 = new Nibbler({
    dataBits: 8,
    codeBits: 5,
    keyString: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567',
    pad: '='
});
jQuery( document ).ready(function() {
	//BT_new_notes_field
var coded=jQuery('#BT_new_notes_field').html();
var cnotes=jQuery('#BT_Customer_Notes').html();
cnotes = cnotes.replace(/\s+/g, '');
if(cnotes ==""){
document.getElementById('BT_Customer_Notes').innerHTML=(base32.decode(coded)); 

}  
//wrkorderdel
jQuery(".wrkorder").click(function(){
	var mid = jQuery(this).attr('mediaid-data');
	var wrksts = jQuery(this).attr('wrkrdrsts-data');
	if( wrksts =='1' ){
	nwrksts = 0;
	}else {
		nwrksts = 1;	
		}
	jQuery.post( "<?php echo  JURI::root();?>codes/wrkrdrtogl.php", { addd: nwrksts, mid: mid })
  .done(function( data ) {
   // alert( "Data Loaded: " + data );
   
   //if( data == 1){
	   if( wrksts == '1' ){
	   jQuery('#wrkrdr_'+mid).attr('wrkrdrsts-data',0);
	   jQuery('#wrkrdr_'+mid).addClass( "wrkrdrn" );
	   jQuery('#wrkrdr_'+mid).removeClass( "wrkrdry" );
	   }else{
		  jQuery('#wrkrdr_'+mid).attr('wrkrdrsts-data',1); 
		  jQuery('#wrkrdr_'+mid).addClass( "wrkrdry" );
		jQuery('#wrkrdr_'+mid).removeClass( "wrkrdrn" );
	   }
	   
 //  }
  });
});

jQuery(".wrkorderdel").click(function(){
	var mid = jQuery(this).attr('mediaid-data');
	jQuery.post( "<?php echo  JURI::root();?>codes/wrkrdrtogl.php", { addd: 3, mid: mid })
  .done(function( data ) {
	    jQuery('#row_'+mid).addClass( "hide" );
	  
  });
});

 Dropzone.options.myAwesomeDropzone = {
          maxFilesize: 2500000000, // MB
		  success: function(file, response){
               // alert(response);
			   //jQuery('#mediafiles_field').val(response);
			   

jQuery('#BT_mediafiles_field').val(jQuery('#BT_mediafiles_field').val()+ ','+response+',');

jQuery('.updateOrder').click();
            },
        };	
});
<?php if ($this->orderID < 25232){
	$db = JFactory::getDBO();
	$sql="Select customer_note from #__virtuemart_orders WHERE virtuemart_order_id='".$this->orderID."'";
	$db->setQuery($sql);
	$oldcnote = $db->loadResult();
	$codez=explode("|@|",$oldcnote);
	?>
	jQuery( document ).ready(function() {

	//BT_new_notes_field
var codedx='<?php echo $codez[0] ; ?>';
var cnotesx=jQuery('#BT_Customer_Notes').html();
cnotesx = cnotesx.replace(/\s+/g, '');
if(cnotesx ==""){
document.getElementById('BT_Customer_Notes').innerHTML=(base32.decode(codedx)); 

}  


	
	});	
	
	<?
}
?>

</script>
<?php //print_r($this);?>
<div style="text-align: left;">
<form name='adminForm' id="adminForm">
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="option" value="com_virtuemart" />
		<input type="hidden" name="view" value="orders" />
		<input type="hidden" name="virtuemart_order_id" value="<?php echo $this->orderID; ?>" />
		<?php echo JHtml::_( 'form.token' ); ?>


<table class="adminlist table table-striped" width="100%">
	<tr>
		<td width="100%">
		<?php echo $this->displayDefaultViewSearch ('COM_VIRTUEMART_ORDER_PRINT_NAME'); ?>
			<span class="btn btn-small " >
		<a class="updateOrder" href="#"><span class="icon-nofloat vmicon vmicon-16-save"></span>
		<?php echo vmText::_('COM_VIRTUEMART_ORDER_SAVE_USER_INFO'); ?></a></span>
		&nbsp;&nbsp;
				<span class="btn btn-small " >
		<a href="#" onClick="javascript:resetOrderHead(event);" ><span class="icon-nofloat vmicon vmicon-16-cancel"></span>
		<?php echo vmText::_('COM_VIRTUEMART_ORDER_RESET'); ?></a>
					</span>
		<?php // echo vmText::_('COM_VIRTUEMART_ORDER_CREATE'); ?></a>

		<?php // $this->createPrintLinks($this->orderbt,$print_link,$deliverynote_link,$invoice_link);
		//echo '<span style="float:right">'.$print_link; echo $deliverynote_link; echo $invoice_link.'</span'; ?>
		<div class="quicklinks" style="float:right;width:auto;height:auto;">
			<a href="#billing-info">Billing Information</a>
			<a href="#file-info">File Attachments</a>
			<a href="#sales-order-info">Sales Order</a>
			<a href="#shipping-info">Shipping & Payment</a>
			<a href="<?php  echo JURI::root();?>index.php?option=com_virtuemart&view=workorders&layout=details&order_number=<?php echo $this->orderbt->order_number; ?>&order_pass=<?php echo  $this->orderbt->order_pass;?>" target=_blank" title="Go To Work Order Print Page">Work Order</a>	
			<a href="<?php  echo JURI::root();?>index.php?option=com_virtuemart&view=invoice&layout=invoice&virtuemart_order_id=<?php echo $this->orderbt->virtuemart_order_id; ?>&order_number=<?php echo $this->orderbt->order_number; ?>&order_pass=<?php echo  $this->orderbt->order_pass;?>" target=_blank" title="Go To Invoice Print Page">Invoice</a>
			
		<div>
		</td>
	</tr>
</table>
</form>

<table class="adminlist table" style="table-layout: fixed;">
	<tr>
		<td>
			<h3 id="order-details">Order Details<br/>&nbsp;</h3>
		</td>
	</tr>
	<tr>
		<td valign="top">
		<table class="adminlist" cellspacing="0" cellpadding="0">
			<thead>
			</thead>
			<?php
			/*	$print_url = juri::root().'index.php?option=com_virtuemart&view=invoice&layout=invoice&tmpl=component&virtuemart_order_id=' . $this->orderbt->virtuemart_order_id . '&order_number=' .$this->orderbt->order_number. '&order_pass=' .$this->orderbt->order_pass;
				$print_link = "<a title=\"".vmText::_('COM_VIRTUEMART_PRINT')."\" href=\"javascript:void window.open('$print_url', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');\"  >";
				$print_link .=   $this->orderbt->order_number . ' </a>';	*/
			?>
			<tr>
				<td class="key"><strong><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_PO_NUMBER') ?></strong></td>
				<?php


				?>
				<td><?php echo $this->orderbt->order_number; ?></td>
				<?php /*<td><?php echo  $print_link;?></td> */ ?>
			</tr>
			<tr>
				<td class="key"><strong><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_PO_PASS') ?></strong></td>
				<td><?php echo  $this->orderbt->order_pass;?></td>
			</tr>
			<tr>
				<td class="key"><strong><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_PO_DATE') ?></strong></td>
				<td><?php  echo vmJsApi::date($this->orderbt->created_on,'LC2',true); ?></td>
			</tr>
			<tr>
				<td class="key"><strong><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_PO_STATUS') ?></strong></td>
				<td><?php echo $this->orderstatuslist[$this->orderbt->order_status]; ?></td>
			</tr>
			<tr>
				<td class="key"><strong><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_NAME') ?></strong></td>
				<td><?php
					if ($this->orderbt->virtuemart_user_id) {
						$userlink = JROUTE::_ ('index.php?option=com_virtuemart&view=user&task=edit&virtuemart_user_id[]=' . $this->orderbt->virtuemart_user_id);
						echo JHtml::_ ('link', JRoute::_ ($userlink), $this->orderbt->order_name, array('title' => vmText::_ ('COM_VIRTUEMART_ORDER_EDIT_USER') . ' ' . $this->orderbt->order_name));
					} else {
						echo $this->orderbt->first_name.' '.$this->orderbt->last_name;
					}
					?>
				</td>
			</tr>
			<tr>
				<td class="key"><strong><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_PO_IPADDRESS') ?></strong></td>
				<td><?php echo $this->orderbt->ip_address; ?></td>
			</tr>
			<?php
			if ($this->orderbt->coupon_code) { ?>
			<tr>
				<td class="key"><strong><?php echo vmText::_('COM_VIRTUEMART_COUPON_CODE') ?></strong></td>
				<td><?php echo $this->orderbt->coupon_code; ?></td>
			</tr>
			<?php } ?>
			<?php
			if ($this->orderbt->invoiceNumber and !shopFunctionsF::InvoiceNumberReserved($this->orderbt->invoiceNumber) ) {
				$invoice_url = juri::root().'index.php?option=com_virtuemart&view=invoice&layout=invoice&format=pdf&tmpl=component&virtuemart_order_id=' . $this->orderbt->virtuemart_order_id . '&order_number=' .$this->orderbt->order_number. '&order_pass=' .$this->orderbt->order_pass;
				$invoice_link = "<a title=\"".vmText::_('COM_VIRTUEMART_INVOICE_PRINT')."\"  href=\"javascript:void window.open('$invoice_url', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');\"  >";
				$invoice_link .=   $this->orderbt->invoiceNumber . '</a>';?>
			<tr>
				<td class="key"><strong><?php echo vmText::_('COM_VIRTUEMART_INVOICE') ?></strong></td>
				<td><?php echo $invoice_link; ?></td>
			</tr>
			<?php } ?>
		</table>
		</td>
		<td valign="top">
		<table class="adminlist table">
			<thead>
				<tr>
					<th><?php echo vmText::_('COM_VIRTUEMART_ORDER_HISTORY_DATE_ADDED') ?></th>
					<th><?php echo vmText::_('COM_VIRTUEMART_ORDER_HISTORY_CUSTOMER_NOTIFIED') ?></th>
					<th><?php echo vmText::_('COM_VIRTUEMART_ORDER_LIST_STATUS') ?></th>
					<th><?php echo JText::_('By') ?></th>
					<th><?php echo vmText::_('COM_VIRTUEMART_COMMENT') ?></th>
				</tr>
			</thead>
			<?php
			foreach ($this->orderdetails['history'] as $this->orderbt_event ) {
				echo "<tr >";
				echo "<td class='key'>". vmJsApi::date($this->orderbt_event->created_on,'LC2',true) ."</td>\n";
				if ($this->orderbt_event->customer_notified == 1) {
					echo '<td align="center">'.vmText::_('COM_VIRTUEMART_YES').'</td>';
				}
				else {
					echo '<td align="center">'.vmText::_('COM_VIRTUEMART_NO').'</td>';
				}
				if(!isset($this->orderstatuslist[$this->orderbt_event->order_status_code])){
					if(empty($this->orderbt_event->order_status_code)){
						$this->orderbt_event->order_status_code = 'unknown';
					}
					$this->orderstatuslist[$this->orderbt_event->order_status_code] = vmText::_('COM_VIRTUEMART_UNKNOWN_ORDER_STATUS');
				}

				echo '<td align="center">'.$this->orderstatuslist[$this->orderbt_event->order_status_code].'</td>';
				$usere =& JFactory::getUser($this->orderbt_event->modified_by);
				echo "<td>".$usere->username."</td>\n";
				echo "<td>".$this->orderbt_event->comments."</td>\n";
				echo "</tr>\n";
			}
			?>
			<tr>
				<td colspan="4">
				<a href="#" class="show_element"><span class="vmicon vmicon-16-editadd"></span><?php echo vmText::_('COM_VIRTUEMART_ORDER_UPDATE_STATUS') ?></a>
				<div style="display: none; background: white; z-index: 100;"
					class="element-hidden vm-absolute"
					id="updateOrderStatus"><?php echo $this->loadTemplate('editstatus'); ?>
				</div>
				</td>
			</tr>

			<?php
				// Load additional plugins
				$_dispatcher = JDispatcher::getInstance();
				$_returnValues1 = $_dispatcher->trigger('plgVmOnUpdateOrderBEPayment',array($this->orderID));
				$_returnValues2 = $_dispatcher->trigger('plgVmOnUpdateOrderBEShipment',array(  $this->orderID));
				$_returnValues = array_merge($_returnValues1, $_returnValues2);
				$_plg = '';
				foreach ($_returnValues as $_returnValue) {
					if ($_returnValue !== null) {
						$_plg .= ('	<td colspan="4">' . $_returnValue . "</td>\n");
					}
				}
				if ($_plg !== '') {
					echo "<tr>\n$_plg</tr>\n";
				}
			?>

		</table>
		</td>
	</tr>
</table>

<form action="index.php" method="post" name="orderForm" id="orderForm"><!-- Update order head form -->
<table class="adminlist table" >
	<?php // if ($this->orderbt->customer_note || true) {
	if(true){ ?>	
	<tr>
		<td valign="top" width="50%">
					<table class="adminlist" cellspacing="0" cellpadding="0">
						<thead>
						<tr>
						<th colspan="2"><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_PAYMENT_SHIPMENT') ?></th>
						</tr>
						</thead>
					<tr>
						<td><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_PAYMENT_LBL') ?></td>
						<?php
						$model = VmModel::getModel('paymentmethod');
						$payments = $model->getPayments();
						$model = VmModel::getModel('shipmentmethod');
						$shipments = $model->getShipments();
						?>
						<td>
							<input  type="hidden" size="10" name="virtuemart_paymentmethod_id" value="<?php echo $this->orderbt->virtuemart_paymentmethod_id; ?>"/>
							<!--
							<? echo VmHTML::select("virtuemart_paymentmethod_id", $payments, $this->orderbt->virtuemart_paymentmethod_id, '', "virtuemart_paymentmethod_id", "payment_name"); ?>
							<span id="delete_old_payment" style="display: none;"><br />
								<input id="delete_old_payment" type="checkbox" name="delete_old_payment" value="1" /> <label class='' for="" title="<?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_PAYMENT_DELETE_DESC'); ?>"><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_PAYMENT_DELETE'); ?></label>
							</span>
							-->
							<?php
							foreach($payments as $payment) {
								if($payment->virtuemart_paymentmethod_id == $this->orderbt->virtuemart_paymentmethod_id) echo $payment->payment_name;
							}
							?>
						</td>
					</tr>
					<tr>
						<td><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_SHIPMENT_LBL') ?></td>
						<td>
							<input type="hidden" size="10" name="virtuemart_shipmentmethod_id" value="<?php echo $this->orderbt->virtuemart_shipmentmethod_id; ?>"/>
							<!--
							<? echo VmHTML::select("virtuemart_shipmentmethod_id", $shipments, $this->orderbt->virtuemart_shipmentmethod_id, '', "virtuemart_shipmentmethod_id", "shipment_name"); ?>
							<span id="delete_old_shipment" style="display: none;"><br />
								<input id="delete_old_shipment" type="checkbox" name="delete_old_shipment" value="1" /> <label class='' for=""><?php echo vmText::_('COM_VIRTUEMART_ORDER_EDIT_CALCULATE'); ?></label>
							</span>
							-->
							<?php
							foreach($shipments as $shipment) {
								if($shipment->virtuemart_shipmentmethod_id == $this->orderbt->virtuemart_shipmentmethod_id) echo $shipment->shipment_name;
							}
							?>
						</td>
					</tr>
					<tr>
						<td class="key"><?php echo vmText::_('COM_VIRTUEMART_DELIVERY_DATE') ?></td>
						<td><input type="text" maxlength="190" class="required" value="<?php echo $this->orderbt->delivery_date; ?>" size="30" name="delivery_date" id="delivery_date_field"></td>
					</tr>
					</table>
				</td>
	</tr>
	<?php } ?>
</table>
&nbsp;
<table width="100%">

	<tr>
		<td>
			<h3 id="billing-info">Billing & Shipping Address<br/>&nbsp;</h3>
		</td>
	</tr>
	<tr>
		<td width="50%" valign="top">
		<table class="adminlist table billz">
			<thead>
				<tr>
					<th  style="text-align: center;" colspan="2"><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_BILL_TO_LBL') ?></th>
				</tr>
			</thead>

			<?php
$btcn='';
$btcnno='';
$btcnx='';
$btcnn='';
			foreach ($this->userfields['fields'] as $_field ) {
//echo print_r($_field);
$ddd= preg_replace('/\s+/', '', $_field['value']);;
				if ($_field['name']=="BT_customer_note" && $ddd==''){
					$_field['value']=$codez[1];
				$btcnn= '<tr>
			<td class="key">
				<label for="BT_customer_note_field">
					Notes and special requests
				</label>
			</td>
			<td>
				<textarea id="BT_customer_note_field" name="BT_customer_note" cols="60" rows="1" class="inputbox" maxlength="2500">'.$codez[1].'</textarea>
			</td>
		</tr>';
		unset ($_field);
				}
				if ($_field['title']=="media_files" || $_field['title']=="new_notes"){
				if ($_field['title']=="media_files"){
					$media=$_field;
				}
				
				
				$btcn .= '		<tr style="display:none">'."\n";
				$btcn .=  '			<td class="key">'."\n";
				$btcn .=  '				<label for="'.$_field['name'].'_field">'."\n";
				$btcn .=  '					'.$_field['title'] . ($_field['required']?' *': '')."\n";
				$btcn .=  '				</label>'."\n";
				$btcn .=  '			</td>'."\n";
				$btcn .=  '			<td>'."\n";
				$btcn .=  '				'.$_field['formcode']."\n";
				$btcn .=  '			</td>'."\n";
				$btcn .=  '		</tr>'."\n"; //*/
				}else{
					if($_field['name']=="BT_phone_2"||$_field['name']=="BT_MobilePhone"||$_field['name']=="BT_track_number"){
					$btcnx .= '		<tr>'."\n";
				$btcnx .= '			<td class="key">'."\n";
				$btcnx .= '				<label for="'.$_field['name'].'_field">'."\n";
				$btcnx .= '		'.$_field['title'] . ($_field['required']?' *': '')."\n";
				$btcnx .= '				</label>'."\n";
				$btcnx .= '			</td>'."\n";
				$btcnx .= '			<td>'."\n";
				$btcnx .= '				'.$_field['formcode']."\n";
				$btcnx .= '			</td>'."\n";
				$btcnx .= '		</tr>'."\n"; //*/BT_customer_note	
						
					}elseif($_field['name']=="BT_Customer_Notes" ){
					$btcnn .= '		<tr>'."\n";
				$btcnn .= '			<td class="key">'."\n";
				$btcnn .= '				<label for="'.$_field['name'].'_field">'."\n";
				$btcnn .= '		'.$_field['title'] . ($_field['required']?' *': '')."\n";
				$btcnn .= '				</label>'."\n";
				$btcnn .= '			</td>'."\n";
				$btcnn .= '			<td>'."\n";
				$btcnn .= '				'.$_field['formcode']."\n";
				$btcnn .= '			</td>'."\n";
				$btcnn .= '		</tr>'."\n"; //*/	
						
					}elseif($_field['name']=="BT_customer_note" ){
					$btcnno .= '		<tr>'."\n";
				$btcnno .= '			<td class="key">'."\n";
				$btcnno .= '				<label for="'.$_field['name'].'_field">'."\n";
				$btcnno .= '		'.$_field['title'] . ($_field['required']?' *': '')."\n";
				$btcnno .= '				</label>'."\n";
				$btcnno .= '			</td>'."\n";
				$btcnno .= '			<td>'."\n";
				$btcnno .= '				'.$_field['formcode']."\n";
				$btcnno .= '			</td>'."\n";
				$btcnno .= '		</tr>'."\n"; //*/	
						
					}else{
				echo '		<tr>'."\n";
				echo '			<td class="key">'."\n";
				echo '				<label for="'.$_field['name'].'_field">'."\n";
				echo '		'.$_field['title'] . ($_field['required']?' *': '')."\n";
				echo '				</label>'."\n";
				echo '			</td>'."\n";
				echo '			<td>'."\n";
				echo '				'.$_field['formcode']."\n";
				echo '			</td>'."\n";
				echo '		</tr>'."\n"; //*/
					}
				}
			}
			echo $btcn;
			
			echo $btcnx;
			echo $btcnn;
			echo $btcnno;
			
		
			
			
			
			?>

		</table>
		</td>
		<td width="50%" valign="top">
		<table class="adminlist table">
			<thead>
				<tr>
					<th   style="text-align: center;" colspan="2"><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_SHIP_TO_LBL') ?></th>
				</tr>
			</thead>

			<?php
			foreach ($this->shipmentfields['fields'] as $_field ) {
				echo '		<tr>'."\n";
				echo '			<td class="key">'."\n";
				echo '				<label for="'.$_field['name'].'_field">'."\n";
				echo '					'.$_field['title'] . ($_field['required']?' *': '')."\n";
				echo '				</label>'."\n";
				echo '			</td>'."\n";
				echo '			<td>'."\n";
				echo '				'.$_field['formcode']."\n";
					echo '			</td>'."\n";
				echo '		</tr>'."\n";
			}
			?>

		</table>
		</td>
	</tr>
</table>
		<input type="hidden" name="task" value="updateOrderHead" />
		<input type="hidden" name="option" value="com_virtuemart" />
		<input type="hidden" name="view" value="orders" />
		<input type="hidden" name="virtuemart_order_id" value="<?php echo $this->orderID; ?>" />
		<input type="hidden" name="old_virtuemart_paymentmethod_id" value="<?php echo $this->orderbt->virtuemart_paymentmethod_id; ?>" />
		<input type="hidden" name="old_virtuemart_shipmentmethod_id" value="<?php echo $this->orderbt->virtuemart_shipmentmethod_id; ?>" />
		<?php echo JHtml::_( 'form.token' ); ?>
</form>

<div class="order-media" style="float:left;width:48%;margin-right:2%">
<h3 id="file-info">Files Attached To This Order<br/>&nbsp;</h3>
<table class="production-table">
<tbody>
<tr class="header-row">
<th class="icon-column">
Icon
</th>
<th class="title-column">
File Name
</th>
<th class="production-column">
Use for production?
</th>
<th class="title-column">
Delete
</th>
</tr>
<?php
$added=trim($media['value'], ","); 
$added=str_replace(",,",",",$added);
$addedarray = explode(",", $added);
$aded=implode("','",$addedarray);
//echo $added; 
$db = JFactory::getDBO();// Joomla database object
$user = JFactory::getUser();
$userid=$user->id;
$sql="select * from #__order_media where id IN ('$aded') AND use_in_wrkrdr!='3'";

$db->setQuery($sql);
$images = $db->loadObjectList();
foreach($images as $image){
if (in_array($image->id, $addedarray)) {
    $class="added";
}else{
	$class="oneimage";
}

if($image->use_in_wrkrdr == "1"){
$styl=' wrkrdry ';}
else
{
	$styl=' wrkrdrn ';
}
$filename=JURI::root().'dz/uploads/'.$image->file_name;
$ext = pathinfo($filename, PATHINFO_EXTENSION);
$ext=strtolower($ext);
if($ext=="png" || $ext=="jpg" || $ext=="gif" ){
			?>
			<tr class="production-files-row" id="row_<?php echo $image->id ;?>_0">
<td class="icon-column" style="width:80px !important; height:100% !important">
<a href="<?php echo $filename; ?>" target="_blank" download ><img src="<?php echo $filename; ?>" width="100%"/></a>
</td>
<td class="title-column" style="word-wrap: break-word">
<?php 
$image->file_name=str_replace('__'," ",$image->file_name) ;
$pos = strpos($image->file_name, '_',1);
$new= substr($image->file_name, $pos+1) ;
$new=str_replace('-'," ",$new);
$new=str_replace('_'," ",$new);
echo $new;?>
</td>
<td class="production-column">
<i class="fa fa-check-square-o fa-2x <?php echo $styl ;?> wrkorder" aria-hidden="true"  mediaid-data="<?php echo $image->id ;?>_0" wrkrdrsts-data="<?php echo $image->use_in_wrkrdr ;?>" id="wrkrdr_<?php echo $image->id ;?>_0"></i>
</td>
<td class="title-column">
<i class="fa fa-ban fa-2x wrkrdrn wrkorderdel" aria-hidden="true"  mediaid-data="<?php echo $image->id ;?>_0"></i>
</td>
</tr>
			<?php
}else{
?>
			<tr class="production-files-row" id="row_<?php echo $image->id ;?>_0>">
<td class="icon-column" style="width:80px !important; height:100% !important">
<a href="<?php echo $filename; ?>" target="_blank" download ><img src="//cdn2.iconfinder.com/data/icons/office-extras/512/Text_Picture_Document-512.png" width="100%"/></a>
</td>
<td class="title-column">
<?php 
$image->file_name=str_replace('__'," ",$image->file_name) ;
$pos = strpos($image->file_name, '_',1);
$new= substr($image->file_name, $pos+1) ;
$new=str_replace('-'," ",$new);
$new=str_replace('_'," ",$new);
echo $new;?>
</td>
<td class="production-column">
<i class="fa fa-check-square-o fa-2x <?php echo $styl ;?> wrkorder" aria-hidden="true"  mediaid-data="<?php echo $image->id ;?>_0" wrkrdrsts-data="<?php echo $image->use_in_wrkrdr ;?>" id="wrkrdr_<?php echo $image->id ;?>_0"></i>
</td>
<td class="title-column">
<i class="fa fa-ban fa-2x wrkrdrn wrkorderdel" aria-hidden="true"  mediaid-data="<?php echo $image->id ;?>_0"></i>
</td>
</tr>	
			<?php	
	
}
			}
$db = JFactory::getDBO();
$sqlr="select fileid from #__joomlavm_ordref where `orderid` = '".$_GET['virtuemart_order_id']."'" ;
$db->setQuery($sqlr);
$ordermedi= $db->loadColumn();

$ordermedia = implode("','", $ordermedi);
$sqll="select * from #__virtueuploads where id IN ('".$ordermedia."') AND use_in_wrkrdr!='3'";

$db->setQuery($sqll);
$imagezz = $db->loadObjectList();

foreach($imagezz as $imagez){

$filename=JURI::root().'dz/uploads/'.$imagez->link;
$ext = pathinfo($filename, PATHINFO_EXTENSION);
$ext=strtolower($ext);

if($ext=="png" || $ext=="jpg" || $ext=="gif" ){
			?>
			<tr class="production-files-row" id="row_<?php echo $imagez->id ;?>_1" >
<td class="icon-column" style="width:80px !important; height:100% !important">
<a href="<?php echo $filename; ?>" target="_blank" download ><img src="<?php echo $filename; ?>" width="100%"/></a>
</td>
<td class="title-column">
<?php 
$imagez->file_name=str_replace('__'," ",$imagez->file_name) ;
$pos = strpos($imagez->file_name, '_',1);
$new= substr($imagez->file_name, $pos+1) ;
$new=str_replace('-'," ",$new);
$new=str_replace('_'," ",$new);
echo $new;?>
</td>
<td class="production-column">
<i class="fa fa-check-square-o fa-2x <?php echo $styl ;?> wrkorder" aria-hidden="true"  mediaid-data="<?php echo $imagez->id ;?>" wrkrdrsts-data="<?php echo $imagez->use_in_wrkrdr ;?>" id="wrkrdr_<?php echo $imagez->id ;?>_1"></i>
</td>
<td class="title-column">
<i class="fa fa-ban fa-2x wrkrdrn wrkorderdel" aria-hidden="true"  mediaid-data="<?php echo $imagez->id ;?>_1"></i>
</td>
</tr>
			<?php
}else{
?>
			<tr class="production-files-row" id="row_<?php echo $imagez->id ;?>_1" >
<td class="icon-column" style="width:80px !important; height:100% !important">
<a href="<?php echo $filename; ?>" target="_blank" download ><img src="//cdn2.iconfinder.com/data/icons/office-extras/512/Text_Picture_Document-512.png" width="100%"/></a>
</td>
<td class="title-column">
<?php 
$imagez->file_name=str_replace('__'," ",$imagez->file_name) ;
$pos = strpos($imagez->file_name, '_',1);
$new= substr($imagez->file_name, $pos+1) ;
$new=str_replace('-'," ",$new);
$new=str_replace('_'," ",$new);
echo $new;?>
</td>
<td class="production-column">
<i class="fa fa-check-square-o fa-2x <?php echo $styl ;?> wrkorder" aria-hidden="true"  mediaid-data="<?php echo $imagez->id ;?>_1" wrkrdrsts-data="<?php echo $imagez->use_in_wrkrdr ;?>" id="wrkrdr_<?php echo $imagez->id ;?>_1"></i>
</td>
<td class="title-column">
<i class="fa fa-ban fa-2x wrkrdrn wrkorderdel" aria-hidden="true"  mediaid-data="<?php echo $imagez->id ;?>_1"></i>
</td>
</tr>	
			<?php	
	
}
			}
			
			?>
			
	
</tbody>
</table>

			<?
			
//get order user

$sql="Select virtuemart_user_id from #__virtuemart_orders WHERE virtuemart_order_id='".$_GET['virtuemart_order_id']."'";
$db->setQuery($sql);
$uidd = $db->loadResult();
?>
</div>
<div class="image_upload_div" style="float:left;width:50%">
<h3>Attach Additional Files to Order<br/>&nbsp;</h3>
			   <form action="<?php echo  JURI::root();?>dz/uploadbe.php?user=<?php echo $uidd;?>&orderid=<?php echo  $_GET['virtuemart_order_id']?>" class="dropzone" id="myAwesomeDropzone" style="width:98%">
				</form>
			</div>
<table width="100%">
	<tr>
		<td colspan="2">
		
		<h3 id="sales-order-info">Sales Order Items</h3>
		<form action="index.php" method="post" name="orderItemForm" id="orderItemForm"><!-- Update linestatus form -->
		<table class="adminlist table"  id="itemTable" >
			<thead>
				<tr>
					<!--<th class="title" width="5%" align="left"><?php echo vmText::_('COM_VIRTUEMART_ORDER_EDIT_ACTIONS') ?></th> -->
					<th class="title" width="3" align="left">#</th>
					<th class="title" width="47" align="left"><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_QUANTITY') ?></th>
					<th class="title" width="*" align="left"><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_NAME') ?></th>
					<th class="title" width="10%" align="left"><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_SKU') ?></th>
					<th class="title" width="10%"><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_ITEM_STATUS') ?></th>
					<th class="title" width="50"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_NET') ?></th>
					<th class="title" width="50"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_BASEWITHTAX') ?></th>
					<th class="title" width="50"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_GROSS') ?></th>
					<th class="title" width="50"><?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_TAX') ?></th>
					<th class="title" width="50"> <?php echo vmText::_('COM_VIRTUEMART_PRODUCT_FORM_PRICE_DISCOUNT') ?></th>
					<th class="title" width="5%"><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_TOTAL') ?></th>
				</tr>
			</thead>
		<?php $i=1;
		foreach ($this->orderdetails['items'] as $item) { ?>
			<!-- Display the order item -->
			<?php
			$lId = '';
			$lId = count($this->orderdetails['items'])==$i? 'id="lItemRow"':'';
			?>
			<tr valign="top" <?php echo $lId?>><?php /*id="showItem_<?php echo $item->virtuemart_order_item_id; ?>" data-itemid="<?php echo $item->virtuemart_order_item_id; ?>">*/ ?>
				<!--<td>
					<?php $removeLineLink=JRoute::_('index.php?option=com_virtuemart&view=orders&orderId='.$this->orderbt->virtuemart_order_id.'&orderLineId='.$item->virtuemart_order_item_id.'&task=removeOrderItem'); ?>
					<a class="vmicon vmicon-16-bug" title="<?php echo vmText::_('remove'); ?>" onclick="javascript:confirmation('<?php echo $removeLineLink; ?>');"></a>

					<a href="javascript:enableItemEdit(<?php echo $item->virtuemart_order_item_id; ?>)"> <?php echo JHtml::_('image',  'administrator/components/com_virtuemart/assets/images/icon_16/icon-16-category.png', "Edit", NULL, true); ?></a>
				</td> -->
				<td>
					<?php echo ($i++)?>
				</td>
				<td>
					<span class='ordereditI'><?php echo $item->product_quantity; ?></span>
					<input class='orderedit' type="text" size="3" name="item_id[<?php echo $item->virtuemart_order_item_id; ?>][product_quantity]" value="<?php echo $item->product_quantity; ?>"/>
				</td>
				<td>
					<span class='ordereditI'><?php echo $item->order_item_name; ?></span>
					<input class='orderedit' type="text"  name="item_id[<?php echo $item->virtuemart_order_item_id; ?>][order_item_name]" value="<?php echo $item->order_item_name; ?>"/><?php
						//echo $item->order_item_name;
						//if (!empty($item->product_attribute)) {
								if(!class_exists('VirtueMartModelCustomfields'))require(VMPATH_ADMIN.DS.'models'.DS.'customfields.php');
								$product_attribute = VirtueMartModelCustomfields::CustomsFieldOrderDisplay($item,'BE');
							if($product_attribute) echo '<div>'.$product_attribute.'</div>';
						//}
						$_dispatcher = JDispatcher::getInstance();
						$_returnValues = $_dispatcher->trigger('plgVmOnShowOrderLineBEShipment',array(  $this->orderID,$item->virtuemart_order_item_id));
						$_plg = '';
						foreach ($_returnValues as $_returnValue) {
							if ($_returnValue !== null) {
								$_plg .= $_returnValue;
							}
						}
						if ($_plg !== '') {
							echo '<table border="0" celspacing="0" celpadding="0">'
								. '<tr>'
								. '<td width="8px"></td>' // Indent
								. '<td>'.$_plg.'</td>'
								. '</tr>'
								. '</table>';
						}
					?>
					<?php if(empty($item->virtuemart_product_id)) { ?>
						<span class='orderedit'>Product ID:</span>
						<input class='orderedit' type="text" size="10" name="item_id[<?php echo $item->virtuemart_order_item_id; ?>][virtuemart_product_id]" value="<?php echo $item->virtuemart_product_id; ?>"/>
					<?php } ?>
				</td>
				<td>
					<span class='ordereditI'><?php echo $item->order_item_sku; ?></span>
					<input class='orderedit' type="text"  name="item_id[<?php echo $item->virtuemart_order_item_id; ?>][order_item_sku]" value="<?php echo $item->order_item_sku; ?>"/>
				</td>
				<td align="center">
					<!--<?php echo $this->orderstatuslist[$item->order_status]; ?><br />-->
					<?php echo $this->itemstatusupdatefields[$item->virtuemart_order_item_id]; ?>

				</td>
				<td align="right" style="padding-right: 5px;">
					<?php
					$item->product_discountedPriceWithoutTax = (float) $item->product_discountedPriceWithoutTax;
					if (!empty($item->product_priceWithoutTax) && $item->product_discountedPriceWithoutTax != $item->product_priceWithoutTax) {
						echo '<span style="text-decoration:line-through">'.$this->currency->priceDisplay($item->product_item_price) .'</span><br />';
						echo '<span >'.$this->currency->priceDisplay($item->product_discountedPriceWithoutTax) .'</span><br />';
					} else {
						echo '<span >'.$this->currency->priceDisplay($item->product_item_price) .'</span><br />'; 
					}
					?>
					<input class='orderedit' type="text" size="8" name="item_id[<?php echo $item->virtuemart_order_item_id; ?>][product_item_price]" value="<?php echo $item->product_item_price; ?>"/>
				</td>
				<td align="right" style="padding-right: 5px;">
					<?php echo $this->currency->priceDisplay($item->product_basePriceWithTax); ?>
					<input class='orderedit' type="text" size="8" name="item_id[<?php echo $item->virtuemart_order_item_id; ?>][product_basePriceWithTax]" value="<?php echo $item->product_basePriceWithTax; ?>"/>
				</td>
				<td align="right" style="padding-right: 5px;">
					<?php echo $this->currency->priceDisplay($item->product_final_price); ?>
					<input class='orderedit' type="text" size="8" name="item_id[<?php echo $item->virtuemart_order_item_id; ?>][product_final_price]" value="<?php echo $item->product_final_price; ?>"/>
				</td>
				<td align="right" style="padding-right: 5px;">
					<?php echo $this->currency->priceDisplay( $item->product_tax); ?>
					<input class='orderedit' type="text" size="12" name="item_id[<?php echo $item->virtuemart_order_item_id; ?>][product_tax]" value="<?php echo $item->product_tax; ?>"/>
					<span style="display: block; font-size: 80%;" title="<?php echo vmText::_('COM_VIRTUEMART_ORDER_EDIT_CALCULATE_DESC'); ?>">
						<input class='orderedit' type="checkbox" name="item_id[<?php echo $item->virtuemart_order_item_id; ?>][calculate_product_tax]" value="1" /> <label class='orderedit' for="calculate_product_tax"><?php echo vmText::_('COM_VIRTUEMART_ORDER_EDIT_CALCULATE'); ?></label>
					</span>
				</td>
				<td align="right" style="padding-right: 5px;">
					<?php echo $this->currency->priceDisplay( $item->product_subtotal_discount); ?>
					<input class='orderedit' type="text" size="8" name="item_id[<?php echo $item->virtuemart_order_item_id; ?>][product_subtotal_discount]" value="<?php echo $item->product_subtotal_discount; ?>"/>
				</td>
				<td align="right" style="padding-right: 5px;">
					<?php 
					$item->product_basePriceWithTax = (float) $item->product_basePriceWithTax;
					if(!empty($item->product_basePriceWithTax) && $item->product_basePriceWithTax != $item->product_final_price ) {
						echo '<span style="text-decoration:line-through" >'.$this->currency->priceDisplay($item->product_basePriceWithTax,$this->currency,$item->product_quantity) .'</span><br />' ;
					}
					elseif (empty($item->product_basePriceWithTax) && $item->product_item_price != $item->product_final_price) {
						echo '<span style="text-decoration:line-through">' . $this->currency->priceDisplay($item->product_item_price,$this->currency,$item->product_quantity) . '</span><br />';
					}
					echo $this->currency->priceDisplay($item->product_subtotal_with_tax);
					?>
					<input class='orderedit' type="hidden" size="8" name="item_id[<?php echo $item->virtuemart_order_item_id; ?>][product_subtotal_with_tax]" value="<?php echo $item->product_subtotal_with_tax; ?>"/>
				</td>
			</tr>

		<?php } ?>
			<tr id="updateOrderItemStatus">

					<td colspan="5">
						<!--
						&nbsp;<a class="newOrderItem" href="#"><span class="icon-nofloat vmicon vmicon-16-new"></span><?php echo vmText::_('COM_VIRTUEMART_NEW_ITEM'); ?></a>
						&nbsp;&nbsp;
						-->
						<a class="updateOrderItemStatus" href="#"><span class="icon-nofloat vmicon vmicon-16-save"></span><?php echo vmText::_('COM_VIRTUEMART_SAVE'); ?></a>
						&nbsp;&nbsp;
						<a href="#" onClick="javascript:cancelEdit(event);" ><span class="icon-nofloat vmicon vmicon-16-remove 4remove"></span><?php echo '&nbsp;'. vmText::_('COM_VIRTUEMART_CANCEL'); ?></a>
						&nbsp;&nbsp;
						<a href="#" onClick="javascript:enableEdit(event);"><span class="icon-nofloat vmicon vmicon-16-edit"></span><?php echo '&nbsp;'. vmText::_('COM_VIRTUEMART_EDIT'); ?></a>
						&nbsp;&nbsp;
						<a href="#" onClick="javascript:addNewLine(event,<?php echo $this->orderdetails['items'][0]->virtuemart_order_item_id ?>);"><span class="icon-nofloat vmicon vmicon-16-new"></span><?php echo '&nbsp;'. vmText::_('JTOOLBAR_NEW'); ?></a>
					</td>

					<td colspan="6">
						<?php // echo JHtml::_('image',  'administrator/components/com_virtuemart/assets/images/vm_witharrow.png', 'With selected'); $this->orderStatSelect; ?>
						&nbsp;&nbsp;&nbsp;

					</td>
			</tr>
		<!--/table -->
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="option" value="com_virtuemart" />
		<input type="hidden" name="view" value="orders" />
		<input type="hidden" name="virtuemart_order_id" value="<?php echo $this->orderID; ?>" />
		<input type="hidden" name="virtuemart_paymentmethod_id" value="<?php echo $this->orderbt->virtuemart_paymentmethod_id; ?>" />
		<input type="hidden" name="virtuemart_shipmentmethod_id" value="<?php echo $this->orderbt->virtuemart_shipmentmethod_id; ?>" />
		<input type="hidden" name="order_total" value="<?php echo $this->orderbt->order_total; ?>" />
		<?php echo JHtml::_( 'form.token' ); ?>
		</form> <!-- Update linestatus form -->
		<!--table class="adminlist" cellspacing="0" cellpadding="0" -->
			<tr>
				<td align="left" colspan="1"><?php $editLineLink=JRoute::_('index.php?option=com_virtuemart&view=orders&orderId='.$this->orderbt->virtuemart_order_id.'&orderLineId=0&tmpl=component&task=editOrderItem'); ?>
				<!-- <a href="<?php echo $editLineLink; ?>" class="modal"> <?php echo JHtml::_('image',  'administrator/components/com_virtuemart/assets/images/icon_16/icon-16-editadd.png', "New Item"); ?>
				New Item </a>--></td>
				<td align="right" colspan="4">
				<div align="right"><strong> <?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_SUBTOTAL') ?>:
				</strong></div>
				</td>
				<td  align="right" style="padding-right: 5px;"><?php echo $this->currency->priceDisplay($this->orderbt->order_subtotal); ?></td>
				<td  align="right" style="padding-right: 5px;">&nbsp;</td>
				<td  align="right" style="padding-right: 5px;">&nbsp;</td>
				<td   align="right" style="padding-right: 5px;"><?php echo $this->currency->priceDisplay($this->orderbt->order_tax); ?></td>
				<td align="right"> <?php echo $this->currency->priceDisplay($this->orderbt->order_discountAmount); ?></td>
				<td width="15%" align="right" style="padding-right: 5px;"><?php echo $this->currency->priceDisplay($this->orderbt->order_salesPrice); ?></td>
			</tr>
			<?php
			/* COUPON DISCOUNT */
			//if (VmConfig::get('coupons_enable') == '1') {

				if ($this->orderbt->coupon_discount > 0 || $this->orderbt->coupon_discount < 0) {
					?>
			<tr>
				<td align="right" colspan="5"><strong><?php echo vmText::_('COM_VIRTUEMART_COUPON_DISCOUNT') ?></strong></td>
				<td  align="right" style="padding-right: 5px;">&nbsp;</td>
				<td  align="right" style="padding-right: 5px;">&nbsp;</td>
				<td  align="right" style="padding-right: 5px;">&nbsp;</td>
				<td  align="right" style="padding-right: 5px;">&nbsp;</td>
				<td  align="right" style="padding-right: 5px;">&nbsp;</td>
				<td   align="right" style="padding-right: 5px;"><?php
				echo $this->currency->priceDisplay($this->orderbt->coupon_discount);  ?></td>
			</tr>
			<?php
				//}
			}?>



	<?php
		foreach($this->orderdetails['calc_rules'] as $rule){
			if ($rule->calc_kind == 'DBTaxRulesBill') { ?>
			<tr >
				<td colspan="5"  align="right"  ><?php echo $rule->calc_rule_name ?> </td>
				<td align="right" colspan="3" > </td>

				<td align="right">
				<!--
					<?php echo  $this->currency->priceDisplay($rule->calc_amount);?>
					<input class='orderedit' type="text" size="8" name="calc_rules[<?php echo $rule->calc_kind ?>][<?php echo $rule->virtuemart_order_calc_rule_id ?>][calc_tax]" value="<?php echo $rule->calc_amount; ?>"/>
				-->
				</td>
				<td align="right"><?php echo  $this->currency->priceDisplay($rule->calc_amount);  ?></td>
				<td align="right"  style="padding-right: 5px;">
					<?php echo  $this->currency->priceDisplay($rule->calc_amount);?>
					<input class='orderedit' type="text" size="8" name="calc_rules[<?php echo $rule->calc_kind ?>][<?php echo $rule->virtuemart_order_calc_rule_id ?>]" value="<?php echo $rule->calc_amount; ?>"/>
				</td>
			</tr>
			<?php
			} elseif ($rule->calc_kind == 'taxRulesBill') { ?>
			<tr >
				<td colspan="5"  align="right"  ><?php echo $rule->calc_rule_name ?> </td>
				<td align="right" colspan="3" > </td>
				<td align="right"><?php echo  $this->currency->priceDisplay($rule->calc_amount);  ?></td>
				<td align="right"> </td>
				<td align="right"  style="padding-right: 5px;">
					<?php echo  $this->currency->priceDisplay($rule->calc_amount);  ?>
					<input class='orderedit' type="text" size="8" name="calc_rules[<?php echo $rule->calc_kind ?>][<?php echo $rule->virtuemart_order_calc_rule_id ?>]" value="<?php echo $rule->calc_amount; ?>"/>
				</td>
			</tr>
			<?php
			 } elseif ($rule->calc_kind == 'DATaxRulesBill') { ?>
			<tr >
				<td colspan="5"   align="right"  ><?php echo $rule->calc_rule_name ?> </td>
				<td align="right" colspan="3" > </td>

				<td align="right"> </td>
				<td align="right"><?php echo  $this->currency->priceDisplay($rule->calc_amount);  ?></td>
				<td align="right"  style="padding-right: 5px;">
					<?php echo  $this->currency->priceDisplay($rule->calc_amount);  ?>
					<input class='orderedit' type="text" size="8" name="calc_rules[<?php echo $rule->calc_kind ?>][<?php echo $rule->virtuemart_order_calc_rule_id ?>]" value="<?php echo $rule->calc_amount; ?>"/>
				</td>
			</tr>

			<?php
			 }

		}
		?>

			<tr>
				<td align="right" colspan="5"><strong><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_SHIPPING') ?>:</strong></td>
				<td  align="right" style="padding-right: 5px;"><?php echo $this->currency->priceDisplay($this->orderbt->order_shipment); ?>
					<input class='orderedit' type="text" size="8" name="order_shipment" value="<?php echo $this->orderbt->order_shipment; ?>"/>
				</td>
				<td  align="right" style="padding-right: 5px;">&nbsp;</td>
				<td  align="right" style="padding-right: 5px;">&nbsp;</td>
				<td  align="right" style="padding-right: 5px;"><?php echo $this->currency->priceDisplay($this->orderbt->order_shipment_tax); ?>
					<input class='orderedit' type="text" size="12" name="order_shipment_tax" value="<?php echo $this->orderbt->order_shipment_tax; ?>"/>
				</td>
				<td  align="right" style="padding-right: 5px;">&nbsp;</td>
				<td  align="right" style="padding-right: 5px;"><?php echo $this->currency->priceDisplay($this->orderbt->order_shipment+$this->orderbt->order_shipment_tax); ?></td>

			</tr>
			<tr>
				<td align="right" colspan="5"><strong><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_PAYMENT') ?>:</strong></td>
				<td align="right" style="padding-right: 5px;"><?php echo $this->currency->priceDisplay($this->orderbt->order_payment); ?>
					<input class='orderedit' type="text" size="8" name="order_payment" value="<?php echo $this->orderbt->order_payment; ?>"/>
				</td>
				<td align="right" style="padding-right: 5px;">&nbsp;</td>
				<td align="right" style="padding-right: 5px;">&nbsp;</td>
				<td align="right" style="padding-right: 5px;"><?php echo $this->currency->priceDisplay($this->orderbt->order_payment_tax); ?>
					<input class='orderedit' type="text" size="12" name="order_payment_tax" value="<?php echo $this->orderbt->order_payment_tax; ?>"/>
				</td>
				<td align="right" style="padding-right: 5px;">&nbsp;</td>
				<td align="right" style="padding-right: 5px;"><?php echo $this->currency->priceDisplay($this->orderbt->order_payment+$this->orderbt->order_payment_tax); ?></td>

			</tr>

			<?php
				$sumRules = array('VatTax'=>array(), 'taxRulesBill'=>array());
				foreach($this->orderdetails['calc_rules'] as $rule){
					if($rule->calc_kind!='VatTax' and $rule->calc_kind!='taxRulesBill') continue;

					if(isset($sumRules[$rule->calc_kind][$rule->virtuemart_calc_id])){
						$sumRules[$rule->calc_kind][$rule->virtuemart_calc_id]->calc_amount += $rule->calc_amount;
					} else {
						$sumRules[$rule->calc_kind][$rule->virtuemart_calc_id] = $rule;
					}
				}
				foreach($sumRules as $calc_kind) {
					foreach( $calc_kind as $rule ) {

						?>
						<tr>
						<td colspan="5" align="right"><?php echo $rule->calc_rule_name ?> </td>
						<td align="right" colspan="3"></td>
						<td align="right" style="padding-right: 5px;">
							<?php echo $this->currency->priceDisplay( $rule->calc_result ); ?>
							<input class='orderedit' type="text" size="8"
								   name="calc_rules[<?php echo $rule->calc_kind ?>][<?php echo $rule->virtuemart_order_calc_rule_id ?>]"
								   value="<?php echo $rule->calc_result; ?>"/>
						</td>
						<td align="right" colspan="2"></td>
						</tr><?php
					}
				}
			?>
			<tr>
				<td align="right" colspan="5"><strong><?php echo vmText::_('COM_VIRTUEMART_ORDER_PRINT_TOTAL') ?>:</strong></td>
				<td align="right" style="padding-right: 5px;">&nbsp;</td>
				<td align="right" style="padding-right: 5px;">&nbsp;</td>
				<td align="right" style="padding-right: 5px;">&nbsp;</td>
				<td align="right" style="padding-right: 5px;">
					<?php echo $this->currency->priceDisplay($this->orderbt->order_billTaxAmount); ?>
					<input class='orderedit' type="text" size="12" name="order_billTaxAmount" value="<?php echo $this->orderbt->order_billTaxAmount; ?>"/>
					<span style="display: block; font-size: 80%;" title="<?php echo vmText::_('COM_VIRTUEMART_ORDER_EDIT_CALCULATE_DESC'); ?>">
						<input class='orderedit' type="checkbox" name="calculate_billTaxAmount" value="1" checked /> <label class='orderedit' for="calculate_billTaxAmount"><?php echo vmText::_('COM_VIRTUEMART_ORDER_EDIT_CALCULATE'); ?></label>
					</span>
				</td>
				<td align="right" style="padding-right: 5px;"><strong><?php echo $this->currency->priceDisplay($this->orderbt->order_billDiscountAmount); ?></strong></td>
				<td align="right" style="padding-right: 5px;"><strong><?php echo $this->currency->priceDisplay($this->orderbt->order_total); ?></strong>
				</td>
			</tr>
			<?php if ($this->orderbt->user_currency_rate != 1.0) { ?>
			<tr>
				<td align="right" colspan="5"><em><?php echo vmText::_('COM_VIRTUEMART_ORDER_USER_CURRENCY_RATE') ?>:</em></td>
				<td  align="right" style="padding-right: 5px;">&nbsp;</td>
				<td  align="right" style="padding-right: 5px;">&nbsp;</td>
				<td  align="right" style="padding-right: 5px;">&nbsp;</td>
				<td  align="right" style="padding-right: 5px;">&nbsp;</td>
				<td  align="right" style="padding-right: 5px;">&nbsp;</td>
				<td   align="right" style="padding-right: 5px;"><em><?php echo  $this->orderbt->user_currency_rate ?></em></td>
			</tr>
			<?php }
			?>
		</table>
		</td>
	</tr>
</table>
&nbsp;
<table width="100%">
<tr>
<td colspan="2">
<h3 id="shipping-info">Shipping & Payment Details</h3>
</td>
</tr>
	<tr>
		<td valign="top" width="50%"><?php
		JPluginHelper::importPlugin('vmshipment');
		$_dispatcher = JDispatcher::getInstance();
		$returnValues = $_dispatcher->trigger('plgVmOnShowOrderBEShipment',array(  $this->orderID,$this->orderbt->virtuemart_shipmentmethod_id, $this->orderdetails));

		foreach ($returnValues as $returnValue) {
			if ($returnValue !== null) {
				echo $returnValue;
			}
		}
		?>
		</td>
		<td valign="top"><?php
		JPluginHelper::importPlugin('vmpayment');
		$_dispatcher = JDispatcher::getInstance();
		$_returnValues = $_dispatcher->trigger('plgVmOnShowOrderBEPayment',array( $this->orderID,$this->orderbt->virtuemart_paymentmethod_id, $this->orderdetails));

		foreach ($_returnValues as $_returnValue) {
			if ($_returnValue !== null) {
				echo $_returnValue;
			}
		}
		?></td>
	</tr>

</table>

</div>

<?php
AdminUIHelper::imitateTabs('end');
AdminUIHelper::endAdminArea();


?>


<script type="text/javascript">


// jQuery('select#order_items_status').change(function() {
	////selectItemStatusCode
	// var statusCode = this.value;
	// jQuery('.selectItemStatusCode').val(statusCode);
	// return false
// });


jQuery( document ).ready(function() {
		var phones = [{ "mask": "###-###-####"}, { "mask": "###-###-##############"}];
    jQuery('#BT_phone_1_field').inputmask({ 
        mask: phones, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
		
    jQuery('#ST_phone_1_field').inputmask({ 
        mask: phones, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
		
		jQuery('#BT_MobilePhone_field').inputmask({ 
        mask: phones, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
			
		jQuery('#ST_MobilePhone_field').inputmask({ 
        mask: phones, 
        greedy: false, 
        definitions: { '#': { validator: "[0-9]", cardinality: 1}} });
		
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.1.62/jquery.inputmask.bundle.js"></script>