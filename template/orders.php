<?php
/**
 *
 * Description
 *
 * @package    VirtueMart
 * @subpackage
 * @author
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: orders.php 9257 2016-07-04 14:40:20Z kkmediaproduction $
 */
// Check to ensure this file is included in Joomla!
defined ('_JEXEC') or die('Restricted access');
AdminUIHelper::startAdminArea ($this);

$styleDateCol = 'style="width:5%;min-width:110px"';

            $order_model = VmModel::getModel('orders');
			$db = JFactory::getDBO();
           
?>
<style>
td.notifyz ul
{
display:inline-block;
float:left;
margin:0;
padding:0;
width:100%;
height:140px;
overflow:auto;
}
td.notifyz ul li
{
display:inline-block;
float:left;
margin:0;
padding:2px 0;
width:100%;
height:auto;
}
a.grey-out, span.grey-out a
{
color:#333;
}
td.statuses strong
{
font-size: 10px;
text-transform: uppercase;
width: 100%;
display: inline-block;
font-weight: 700;
}
</style>
<form action="index.php?option=com_virtuemart&view=orders" method="post" name="adminForm" id="adminForm">
	<div id="header">
		<div id="filterbox">
			<table>
				<tr>
					<td align="left" style="min-width:480px;width:40%;">
						<?php echo $this->displayDefaultViewSearch ('COM_VIRTUEMART_ORDER_PRINT_NAME'); ?>
						<?php echo vmText::_ ('COM_VIRTUEMART_ORDERSTATUS') . ':' . $this->lists['state_list']; ?>

					</td>
					<td align="right" style="min-width:290px;width:30%;" colspan="2">
						<?php echo vmText::_ ('COM_VIRTUEMART_BULK_ORDERSTATUS') . $this->lists['bulk_state_list']; ?>
					</td>
					<td>
						<?php echo $this->lists['vendors'] ?>
					</td>
				</tr>
			</table>
		</div>
		<div id="resultscounter"><?php echo $this->pagination->getResultsCounter (); ?></div>
	</div>
<div style="text-align: left;">
	<table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
		<thead>
		<tr>
			<th class="admin-checkbox"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)"/></th>
			<th width="8%"><?php echo $this->sort ('order_number', 'COM_VIRTUEMART_ORDER_LIST_NUMBER')  ?></th>
			<th width="20%"><?php echo $this->sort ('order_name', 'COM_VIRTUEMART_ORDER_PRINT_NAME')  ?></th>
			<th width="11%"><?php echo $this->sort ('payment_method', 'COM_VIRTUEMART_ORDER_PRINT_PAYMENT_LBL')  ?></th>
			<th style="width:5%;"><?php echo $this->sort ('shipment_method', 'COM_VIRTUEMART_ORDER_PRINT_SHIPMENT_LBL')  ?><?php //echo vmText::_ ('COM_VIRTUEMART_PRINT_VIEW'); ?></th>
			<th class="admin-dates"><?php echo $this->sort ('created_on', 'COM_VIRTUEMART_ORDER_CDATE')  ?></th>
			<th class="admin-dates"><?php echo $this->sort ('modified_on', 'COM_VIRTUEMART_ORDER_LIST_MDATE')  ?></th>
			<th><?php echo $this->sort ('order_status', 'COM_VIRTUEMART_STATUS')  ?></th>
			<th width="13%"><?php echo JText::_('Send Email'); ?></th>
			<th width="13%">Key Order Statuses</th>
			<th width="10%"><?php echo $this->sort ('order_total', 'COM_VIRTUEMART_TOTAL')  ?></th>
			<th>Print Views</th>

		</tr>
		</thead>
		<tbody>
		<?php
		if (count ($this->orderslist) > 0) {
			$i = 0;
			$k = 0;
			$keyword = vRequest::getCmd ('keyword');
$model = VmModel::getModel('shipmentmethod');
			$shipments = $model->getShipments();
			foreach ($this->orderslist as $key => $order) {
				$checked = JHtml::_ ('grid.id', $i, $order->virtuemart_order_id);
				?>
			<tr class="row<?php echo $k; ?>">
				<!-- Checkbox -->
				<td class="admin-checkbox"><?php echo $checked; ?></td>
				<!-- Order id -->
				<?php
				$link = 'index.php?option=com_virtuemart&view=orders&task=edit&virtuemart_order_id=' . $order->virtuemart_order_id;
				?>
				<td><strong><?php echo JHtml::_ ('link', JRoute::_ ($link, FALSE), $order->order_number, array('title' => vmText::_ ('COM_VIRTUEMART_ORDER_EDIT_ORDER_NUMBER') . ' ' . $order->order_number)); ?></strong>
					<br/>
					<span class="grey-out">id#&nbsp;
						<?php echo JHtml::_ ('link', JRoute::_ ($link, FALSE), $order->virtuemart_order_id, array('title' => vmText::_ ('COM_VIRTUEMART_ORDER_EDIT_ORDER_ID') . ' ' . $order->virtuemart_order_id)); ?>
					</span>
				</td>

				<td>
					<?php
					if ($order->virtuemart_user_id) {
						$userlink = JROUTE::_ ('index.php?option=com_virtuemart&view=user&task=edit&virtuemart_user_id[]=' . $order->virtuemart_user_id, FALSE);
						echo JHtml::_ ('link', JRoute::_ ($userlink, FALSE), $order->order_name, array('title' => vmText::_ ('COM_VIRTUEMART_ORDER_EDIT_USER') . ' ' .  $order->order_name));
					} else {
						echo $order->order_name;
					}
					?><br/>
					<p><?php
					 $myorder = $order_model->getOrder($order->virtuemart_order_id);
					$info=$myorder['details']['BT'];
					$infos=$myorder['details']['ST'];
					//echo ;
					$query="SELECT `state_name` FROM `jos_virtuemart_states` WHERE`virtuemart_state_id`='".$info->virtuemart_state_id."'";
					$db->setQuery($query);
					$state = $db->loadResult();
						//echo "c<br>".$myorder['details']['BT'];//->virtuemart_state_id;
						$addressz=$order->city. '+' .$state."+".$info->zip."+".$infos->address_1;
					?> 		<a href="https://www.google.ca/maps/place/<?php echo $addressz;?>" title="Find this location on a map" target=_blank" class="grey-out map" ><?php echo $order->city. ',' .$state;?></a><!-- Prov State here please Sameh --><br/>
					
					<?php
					
					?>
			
					
					<a class="grey-out phone" href="tel:+1<?php echo $order->phone; //print_r($order); ?>" title ="Click to call this number"><?php echo $order->phone;  ?></a><br/>
					<a class="grey-out mail" href="mailto:<?php echo $order->order_email; ?>" target="_blank" title="send a message to <?php echo $order->order_name; ?>">
					<?php
					echo $order->order_email;
					?></a>
					</p>
				</td>
				<!-- Payment method -->
				<td><?php echo $order->payment_method; ?></td>
				<!-- Print view -->
				<?php
					$this->createPrintLinks($order,$print_link,$deliverynote_link,$invoice_link);
				?>
				<td><input type="hidden" size="10" name="virtuemart_shipmentmethod_id" value="<?php echo $this->orderbt->virtuemart_shipmentmethod_id; ?>"/>
							<!--
							<? 
							
							echo VmHTML::select("virtuemart_shipmentmethod_id", $shipments, $order->virtuemart_shipmentmethod_id, '', "virtuemart_shipmentmethod_id", "shipment_name"); ?>
							<span id="delete_old_shipment" style="display: none;"><br />
								<input id="delete_old_shipment" type="checkbox" name="delete_old_shipment" value="1" /> <label class='' for=""><?php echo vmText::_('COM_VIRTUEMART_ORDER_EDIT_CALCULATE'); ?></label>
							</span>
							-->
							<?php
							foreach($shipments as $shipment) {
								if($shipment->virtuemart_shipmentmethod_id == $order->virtuemart_shipmentmethod_id) echo $shipment->shipment_name;
							}
							?><?php /*echo $print_link; *///echo $deliverynote_link; echo $invoice_link; ?></td>
				<!-- Order date -->
				<td><?php echo vmJsApi::date ($order->created_on, 'LC2', TRUE); ?></td>
				<!-- Last modified -->
				<td><?php echo vmJsApi::date ($order->modified_on, 'LC2', TRUE); ?></td>
				<!-- Status -->
				<td style="position:relative;">
					<?php echo JHtml::_ ('select.genericlist', $this->orderstatuses, "orders[" . $order->virtuemart_order_id . "][order_status]", 'class="orderstatus_select"', 'order_status_code', 'order_status_name', $order->order_status, 'order_status' . $i, TRUE); ?>
					<input type="hidden" name="orders[<?php echo $order->virtuemart_order_id; ?>][current_order_status]" value="<?php echo $order->order_status; ?>"/>
					<input type="hidden" name="orders[<?php echo $order->virtuemart_order_id; ?>][coupon_code]" value="<?php echo $order->coupon_code; ?>"/>
					<br/>
					<textarea class="element-hidden vm-order_comment vm-showable" name="orders[<?php echo $order->virtuemart_order_id; ?>][comments]" cols="5" rows="5"></textarea>
					<?php echo JHtml::_ ('link', '#', vmText::_ ('COM_VIRTUEMART_ADD_COMMENT'), array('class' => 'show_comment')); ?>
				</td>
				<!-- Update -->
				<td class="notifyz">
							<ul>
								<li>
									<?php echo VmHTML::checkbox('orders_'.$order->virtuemart_order_id.'_customer_notified', 0 ) . JText::_('Customer'); ?>
									<div style="display:none;">
										<br />
										<?php echo VmHTML::checkbox('orders['.$order->virtuemart_order_id.'][customer_send_comment]', 1 ) . JText::_('COM_VIRTUEMART_ORDER_HISTORY_INCLUDE_COMMENT'); ?>
										<br />
										<?php echo VmHTML::checkbox('orders['.$order->virtuemart_order_id.'][update_lines]', 1 ) . JText::_('COM_VIRTUEMART_ORDER_UPDATE_LINESTATUS'); ?>
									</div>
								</li>
								<li>
									<?php echo VmHTML::checkbox('orders_'.$order->virtuemart_order_id.'_verification_notified', 0 ) . JText::_('Verification'); ?>
								</li>
								<li>
									<?php echo VmHTML::checkbox('orders_'.$order->virtuemart_order_id.'_art_notified', 0 ) . JText::_('Art Dept.'); ?>
								</li>
								<li>
									<?php echo VmHTML::checkbox('orders_'.$order->virtuemart_order_id.'_shipping_notified', 0 ) . JText::_('Shipping'); ?>
								</li>
								<li>
									<?php echo VmHTML::checkbox('orders_'.$order->virtuemart_order_id.'_oadmin_notified', 0 ) . JText::_('Office Admin'); ?>
								</li>
								<li>
									<?php echo VmHTML::checkbox('orders_'.$order->virtuemart_order_id.'_agent_notified', 0 ) . JText::_('Agent'); ?>
								</li>
								<li>
									<?php  echo VmHTML::checkbox('orders_'.$order->virtuemart_order_id.'_fax_notified', 0 ) . JText::_('Fax Verification'); ?>
								</li>
								<li>
									<?php echo VmHTML::checkbox('orders_'.$order->virtuemart_order_id.'_engraver_notified', 0 ) . JText::_('Engraver'); ?>
								</li>
								<li>
									<?php echo VmHTML::checkbox('orders_'.$order->virtuemart_order_id.'_production_notified', 0 ) . JText::_('Production'); ?>
								</li>
								<li>
									<?php echo VmHTML::checkbox('orders_'.$order->virtuemart_order_id.'_leader_notified', 0 ) . JText::_('Sales Admin'); ?>
								</li>
						</ul>
				</td>
				<!-- Total -->
				<?php
				$db = JFactory::getDBO();
				$q="SELECT `created_on`,`order_status_code` FROM `#__virtuemart_order_histories`  where `order_status_code` IN ('C','I','S') AND `virtuemart_order_id` = ".$order->virtuemart_order_id ." GROUP BY `order_status_code`";
				$db->setQuery($q);
				$rows = $db->loadObjectList();
				$sts=array();
				foreach($rows as $row){
				$sts[$row->order_status_code]=$row->created_on;
					
				}
				
				?>
				<td class="statuses"><!-- key order statuses here please sameh-->
					<strong>Order Verified:</strong><br/>
					<p><?php echo $sts['C'];?></p>
					<strong>Released to Production:</strong><br/>
					<p><?php echo $sts['I'];?></p>
					<strong>Order Shipped:</strong><br/>
					<p><?php echo $sts['S'];?></p>
					</td>
				<td><?php echo $order->order_total; ?></td>
				<td><a target="_blank" href="<?php  echo JURI::root();?>index.php?option=com_virtuemart&view=workorders&layout=details&order_number=<?php echo $order->order_number;?>&order_pass=<?php echo $order->order_pass;?>"> Work Order </a></td>
			</tr>
				<?php
				$k = 1 - $k;
				$i++;
			}
		}
		?>
		</tbody>
		<tfoot>
		<tr>
			<td colspan="12">
				<?php echo $this->pagination->getListFooter (); ?>
			</td>
		</tr>
		</tfoot>
	</table>
</div>
	<!-- Hidden Fields -->
	<?php echo $this->addStandardHiddenToForm (); ?>
</form>
<?php AdminUIHelper::endAdminArea ();

$j = 'function set2status(){

	var newStatus = jQuery("#order_status_code_bulk").val();

	var customer_notified = jQuery("input[name=\'customer_notified\']").is( ":checked" );
	var customer_send_comment = jQuery("input[name=\'customer_send_comment\']").is( ":checked" );
	var update_lines = jQuery("input[name=\'update_lines\']").is( ":checked" );
	var comments = jQuery("textarea[name=\'comments\']").val();

	field = document.getElementsByName("cid[]");
	var fname = "";
	var sel = 0;
	for (i = 0; i < field.length; i++){
		if (field[i].checked){
			fname = "orders[" + field[i].value + "]";
			jQuery("input[name=\'"+fname+"[customer_notified]\']").prop("checked",customer_notified);
			jQuery("input[name=\'"+fname+"[customer_send_comment]\']").prop("checked",customer_send_comment);
			jQuery("input[name=\'"+fname+"[update_lines]\']").prop("checked",update_lines);
			jQuery("textarea[name=\'"+fname+"[comments]\']").text(comments);
			console.log("Mist ",jQuery("input[name=\'"+fname+"[comments]\']"));
			jQuery("#order_status"+i).val(newStatus).trigger("chosen:updated").trigger("liszt:updated");
		}
	}

}';
vmJsApi::addJScript('set2status',$j);

$j = "jQuery('.show_comment').click(function() {
		jQuery(this).prev('.element-hidden').show();
		return false
		});
		jQuery('.element-hidden').mouseleave(function() {
		jQuery(this).hide();
		});
		jQuery('.element-hidden').mouseout(function() {
		jQuery(this).hide();
	});";
vmJsApi::addJScript('show_comment',$j);

$orderstatusForShopperEmail = VmConfig::get('email_os_s',array('U','C','S','R','X'));
if(!is_array($orderstatusForShopperEmail)) $orderstatusForShopperEmail = array($orderstatusForShopperEmail);
$jsOrderStatusShopperEmail = vmJsApi::safe_json_encode($orderstatusForShopperEmail);

$j ='
	jQuery(document).ready(function() {
		jQuery(".orderstatus_select").change( function() {

			var name = jQuery(this).attr("name");
			var brindex = name.indexOf("orders[");
			if ( brindex >= 0){
				//yeh, yeh, maybe not the most elegant way, but it does, what it should
				var s = name.indexOf("[")+1;
				var e = name.indexOf("]");
				var id = name.substring(s,e);

				var orderstatus = '.$jsOrderStatusShopperEmail.';
				var selected = jQuery(this).val();
				var selStr = "[name=\"orders["+id+"][customer_notified]\"]";
				var elem = jQuery(selStr);

				if(jQuery.inArray(selected, orderstatus)!=-1){
					elem.attr("checked",true);
					// for the checkbox    
					jQuery(this).parent().parent().find("input[name=\"cid[]\"]").attr("checked",true);
				} else {
					elem.attr("checked",false);
				}
			}
		});
	});';

vmJsApi::addJScript('setChecksByOrderStatus',$j);