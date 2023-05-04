<?php
/**
* @copyright Copyright (C) 2009 JoomlaPraise. All rights reserved.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<?php // Detecting Home
$menu = & JSite::getMenu();
if ($menu->getActive() == $menu->getDefault()) {
$siteHome = 1;
}

// Detecting Active Component
$option = JRequest::getCmd('option', '');
$layout = JRequest::getCmd('layout', '');
$task = JRequest::getCmd('task', '');
if($task == "edit" || $layout == "form" ) {
$fullWidth = 1;
}
?>
<jdoc:include type="head" />
<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template ?>/css/template.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template ?>/css/design.css" type="text/css" />
<style type="text/css">
<?php if($this->params->get('fontFamily') == "times") { ?>
body{font-family:"Times New Roman", Times, serif;}
<?php } elseif($this->params->get('fontFamily') == "courier") { ?>
body{font-family:"Courier New", Courier, monospace;}
<?php } elseif($this->params->get('fontFamily') == "georgia") { ?>
body{font-family:Georgia,"Times New Roman", Times, serif;}
<?php } ?>
<?php if($this->params->get('headingFontFamily') == "arial") { ?>
h1, h2, h3, h4, h5, h6, .componentheading, .contentheading{font-family:Arial, Helvetica, sans-serif;}
<?php } elseif($this->params->get('headingFontFamily') == "times") { ?>
h1, h2, h3, h4, h5, h6, .componentheading, .contentheading{font-family:"Times New Roman", Times, serif;}
<?php } elseif($this->params->get('headingFontFamily') == "courier") { ?>
h1, h2, h3, h4, h5, h6, .componentheading, .contentheading{font-family:"Courier New", Courier, monospace;}
<?php } ?>
<?php if(($this->countModules('left') == 0) && ($this->countModules('right') == 0)) { ?>
#mainbody{width:100%;} #content{width:100%;}
<?php } ?>
<?php if(($this->countModules('left') >= 1) && ($this->countModules('right') == 0)) { ?>
#content{width:100%;}
<?php } ?>
<?php if(($this->countModules('left') == 0) && ($this->countModules('right') >= 1)) { ?>
#mainbody{width:100%;} #sidebar2{width:160px;} #content{width:640px;}
<?php } ?>
<?php if($this->params->get('backgroundColor')){ ?>
body{background-color:<?php echo $this->params->get('backgroundColor'); ?>} 
<?php } ?>
<?php if($this->params->get('containerColor')){ ?>
#container{background-color:<?php echo $this->params->get('containerColor'); ?>} 
<?php } ?>
<?php if($this->params->get('headerColor')){ ?>
#header{background:<?php echo $this->params->get('headerColor'); ?>} #header .inside{background:none;}
<?php } ?>
<?php if($this->params->get('fontColor')){ ?>
body{color:<?php echo $this->params->get('fontColor'); ?>}
<?php } ?>
<?php if($this->params->get('headingColor')){ ?>
h1, h2, h3, h4, h5, h6, .componentheading, .contentheading{color:<?php echo $this->params->get('headingColor'); ?>}
<?php } ?>
<?php if($this->params->get('linkColor')){ ?>
a:link, a:active, a:visited{color:<?php echo $this->params->get('linkColor'); ?>}
<?php } ?>
<?php if($this->params->get('linkHoverColor')){ ?>
a:hover{color:<?php echo $this->params->get('linkHoverColor'); ?>}
<?php } ?>
<?php 
// Detecting Active Component
if($option == "com_projectfork"){ ?>
#mainbody{width:100%;} #content{width:100%;} #sidebar{display:none;} #sidebar2{display:none;}
<?php } ?>
<?php if($this->params->get('moveLogo')) { ?>
#logo-container{width:100%;} #logo{width:220px;} #newsflash{width:580px;}
<?php } ?>
<?php if($fullWidth){ ?>
#mainbody{width:100%;} #content{width:100%;} #sidebar{display:none;} #sidebar2{display:none;}
<?php } ?>

</style>
</head>
<body>
<div id="bar"></div>
<div id="wrapper">
	<?php if (($this->countModules('user3')) || ($this->countModules('user4'))) { ?>
	<div id="navigation">
    	<div class="pad">
        	<?php if ($this->countModules('user3')) { ?>
            <div id="mainmenu">
                <jdoc:include type="modules" name="user3" />
            </div>
            <?php } ?>
            <?php if ($this->countModules('user4')) { ?>
            <div id="search">
                <jdoc:include type="modules" name="user4" />
            </div>
            <?php } ?>
            <div class="clr"></div>
		</div>
    </div>
    <?php } ?>
	<div id="container">
   	  <div class="pad">
        <div id="logo-container">
            <a href="<?php echo $mainframe->getCfg('live_site'); ?>" id="logo" title="<?php echo $mainframe->getCfg('sitename'); ?>">
            <h1><?php echo $mainframe->getCfg('sitename'); ?></h1>
            </a>
            <?php if ($this->countModules('top')) { ?>
            <div id="newsflash">
                <jdoc:include type="modules" name="top" />
            </div>
            <?php } ?>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
        <div id="mainbody">
        		<jdoc:include type="message" />
                <?php if ($this->countModules('breadcrumb')) { ?>
            	<div id="pathway">
                    <jdoc:include type="modules" name="breadcrumb" />
                </div>
                <?php } ?>  
                <?php if ($this->countModules('advert1')) { ?>
            	<div id="advert">
                    <jdoc:include type="modules" name="advert1" />
                </div>
                <?php } ?>  
                <?php if (($this->countModules('user1')) || ($this->countModules('user2'))) { ?>
                <div id="elements">
                  <?php if ($this->countModules('user1')) { ?>
               	  <div id="elements-l">
                      <jdoc:include type="modules" name="user1" style="xhtml"/>
                	</div>
                    <?php } ?>
                    <?php if ($this->countModules('user2')) { ?>
                    <div id="elements-r">
                      <jdoc:include type="modules" name="user2" style="xhtml"/>
                    </div>
                    <?php } ?>
                	<div class="clr"></div>
                </div>
                <?php } ?>
                <div id="content">
					<?php if ($this->countModules('advert2')) { ?>
                    <div id="advert">
                        <jdoc:include type="modules" name="advert2" />
                    </div>
                    <?php } ?>
                	<jdoc:include type="component" />
                    <?php if ($this->countModules('advert3')) { ?>
                    <div id="advert">
                        <jdoc:include type="modules" name="advert3" />
                    </div>
                    <?php } ?>
                </div>
                <?php if ($this->countModules('right')) { ?>
                	<div id="sidebar2">
                    	<jdoc:include type="modules" name="right" style="xhtml"/>
                    </div>
                <?php } ?>
                <div class="clr"></div>
        	</div>
            <?php if ($this->countModules('left')) { ?>
            <div id="sidebar">
            	<jdoc:include type="modules" name="left" style="xhtml"/>
            </div>
            <?php } ?>
        <div class="clr"></div>
        </div>
    </div>
    <?php if (($this->countModules('footer')) || ($this->countModules('user5'))) { ?>
    <div id="footer">
    	<div class="pad">
        	<?php if ($this->countModules('footer')) { ?>
        	<div id="copy">
            	<jdoc:include type="modules" name="footer" />
                <?php /* You are free to remove or edit the following */ print "<a href=\"http://www.joomlapraise.com\" title=\"Joomla! Templates and Extensions\" target=\"_blank\">Joomla! Templates &amp; Extensions</a> by <a href=\"http://www.joomlapraise.com\" title=\"Joomla! Templates and Extensions\" target=\"_blank\">JoomlaPraise</a>"; ?>
            </div>
            <?php } ?>
            <?php if ($this->countModules('user5')) { ?>
            <div id="link">
            	<jdoc:include type="modules" name="user5" />
            </div>
            <?php } ?>
        	<div class="clr"></div>
        </div>
    </div>
    <?php } ?>
</div>
</body>
</html>
