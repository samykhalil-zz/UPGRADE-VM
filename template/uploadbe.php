<?php
define('_JEXEC', 1);
 
define( 'DS', DIRECTORY_SEPARATOR );
 
define( 'JPATH_BASE', realpath(dirname(__FILE__).DS.'..' ));
 
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
 
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' ); 


$db = JFactory::getDBO();

$app = JFactory::getApplication('site');
$app->initialise();
if(!empty($_FILES)){

$db = JFactory::getDBO();// Joomla database object
//$user = JFactory::getUser();
$userid=$_GET['user'];
$orderid=$_GET['orderid'];
    $targetDir = "uploads/";
    $fileName = $userid.'_'.'_'.date("Y-m-d-H-i-s_").$_FILES['file']['name'];
    $targetFile = $targetDir.$fileName;
    
    if(move_uploaded_file($_FILES['file']['tmp_name'],$targetFile)){
        //insert file information into db table
        $sql="INSERT INTO jos_order_media (file_name,user_id,orderid) VALUES ('$fileName','$userid','$orderid')";
		$db->setQuery($sql);
		$count = $db->loadResult();
		$file_id = $db->insertid();
		echo $file_id;
			
    }
    
}
?>