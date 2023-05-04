<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted index access' );
define( 'YOURBASEPATH', dirname(__FILE__) );

$live_site                  = $mainframe->getCfg('live_site');
$template_path              = $this->baseurl . '/templates/' .  $this->template;
$show_flashheader           = ($this->params->get("showFlashheader", 1)  == 0)?"false":"true";
$show_logo                  = ($this->params->get("showLogo", 1)  == 0)?"false":"true";
$show_date                  = ($this->params->get("showDate", 1)  == 0)?"false":"true";
$show_breadcrumbs           = ($this->params->get("showBreadcrumbs", 1)  == 0)?"false":"true";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<jdoc:include type="head" />

<link rel="shortcut icon" href="<?php echo $this->baseurl; ?>/images/favicon.ico" />
<link href="<?php echo $this->baseurl ?>/templates/system/css/system.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->baseurl ?>/templates/system/css/general.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/css/template_css.css" rel="stylesheet" type="text/css" />

<!--[if lte IE 6]>
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/ie6.css" type="text/css" />
<![endif]-->

</head>
<body>
<div id="bgr">

<div id="wrapper">
<div id="tophead">
<?php if($this->countModules('user4')) : ?>

<?php endif; ?>
<!-- BEGIN: LOGO -->
<?php if($show_logo == "true") : ?>

<?php endif; ?>
<!-- END: LOGO -->



</div>
<?php if( $this->countModules('user3') ) {?>

<?php } ?>

<div id="wrapper_2">
<div id="holder">


<div id="content">
<?php if($this->countModules('left') and JRequest::getCmd('layout') != 'form') : ?>
<div id="left">
<jdoc:include type="modules" name="left" style="rounded" />
</div>
<?php endif; ?>

<?php if($this->countModules('right') and JRequest::getCmd('layout') != 'form') : ?>
<div id="main">
<?php else: ?>
<div id="main_full">
<?php endif; ?>
<div class="nopad">
<jdoc:include type="message" />



<jdoc:include type="component" />
<jdoc:include type="modules" name="user2" style="" />
</div>
</div>

<?php if($this->countModules('right') and JRequest::getCmd('layout') != 'form') : ?>
<div id="right">
<jdoc:include type="modules" name="right" style="rounded" />
</div>
<?php endif; ?>
<div class="clr"></div>
</div>
</div>

<!--footer start-->
<div id="footer">
<div id="footer_in">
<div>
<div style="text-align: center; padding: 18px 0 0;"><img src="http://variability.org//templates/jofree/images/footer.jpg" width="930" height="72" border="0" usemap="#Map" />
  <map name="Map" id="Map">
    <area shape="rect" coords="20,18,244,57" href="http://ucsd.edu" target="_blank" />
    <area shape="rect" coords="263,15,382,58" href="http://www.ucla.edu/" target="_blank" />
    <area shape="rect" coords="408,13,473,61" href="http://www.umich.edu/" target="_blank" />
    <area shape="rect" coords="503,21,648,58" href="http://www.stanford.edu/" target="_blank" />
    <area shape="rect" coords="668,21,817,60" href="http://uci.edu/" target="_blank" />
    <area shape="rect" coords="848,5,895,64" href="http://illinois.edu/" target="_blank" />
  </map>
</div>
</div>
</div>
</div>
<!--footer end-->
</div>
</div>
</div>
</div>
<jdoc:include type="modules" name="debug" />
</body>
</html>
