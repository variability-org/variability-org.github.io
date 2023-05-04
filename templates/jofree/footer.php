<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

global $_VERSION;
require_once('libraries/joomla/utilities/date.php');
$date  = new JDate();
$config = new JConfig();
// NOTE - You may change this file to suit your site needs
?>
Copyright &copy; <?php echo $date->toFormat( '2005 - %Y' ) . ' ' . $config->sitename;?>. 
Supported by: <?php @include('images/img2.tif')?> and <?php @include('images/img1.tif')?><br />
<!-- <?php echo $_VERSION->URL; ?>  -->
