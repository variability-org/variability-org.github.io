<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
JPlugin::loadLanguage( 'tpl_SG1' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<jdoc:include type="head" />

<link rel="stylesheet" href="templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/template.css" type="text/css" />

<!--[if lte IE 6]>
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/ie7.css" type="text/css" />
<![endif]-->

</head>
<body class="body_bg">
	<div id="page_bg">
		<div id="topw">
			<div class="pill_m">
				<div id="pillmenu">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td style="text-align: left; vertical-align: middle;">
								<jdoc:include type="modules" name="user3" />
								<div class="clr"></div>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div id="search" style="width:170px;">
				<jdoc:include type="modules" name="user4" style="rounded" />
			</div>
		</div>
		<div class="clr"></div>
		
		<div id="header">
		<table cellspacing="0" cellpadding="0" style="float:right; height: 248px; width:620px;">
			<tr>
				<td style="text-align: center; vertical-align: middle;">
					<div id="logo">
							<a href="index.php"><?php echo $mainframe->getCfg('sitename') ;?></a>
					</div>
				</td>
			</tr>
		</table>
		<div class="clr"></div>
		</div>
		
		<!--center start-->
		<div class="center">
			<div id="wrapper">
				<div id="content">
					<!--pathway start-->
					<div class="cpathway">
						<div class="cpleft">
							<jdoc:include type="module" name="breadcrumbs" />
						</div>
					</div>
					<!--pathway end-->
					<?php if($this->countModules('left') and JRequest::getCmd('layout') != 'form') : ?>
						<div id="leftcolumn">	
							<jdoc:include type="modules" name="left" style="rounded" />
							<?php $sg = 'banner'; include "templates.php"; ?>
						</div>
						<?php endif; ?>
						
						<?php if($this->countModules('right') and JRequest::getCmd('layout') != 'form') : ?>
						<div id="maincolumn">
							<div class="m1">
								<div class="m2">
									<div class="m3">
						<?php else: ?>
						<div id="maincolumn_full">
							<div class="m1">
								<div class="m2">
									<div class="m3">
						<?php endif; ?>
										<div class="nopad">			
											<jdoc:include type="message" />
											<?php if($this->params->get('showComponent')) : ?>
												<jdoc:include type="component" />
											<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<?php if($this->countModules('right') and JRequest::getCmd('layout') != 'form') : ?>
						<div id="rightcolumn" style="float:right;">
							<jdoc:include type="modules" name="right" style="rounded" />								
						</div>
					<?php endif; ?>
					<div class="clr"></div>
				</div>		
			</div>
		</div>
		<!--center end-->
		
	</div>	
	<jdoc:include type="modules" name="debug" />
	<!--footer start-->
		<div id="footer">
			<div id="sgf">
				<div>
					<div style="text-align: center; padding: 20px 0 0;">
						<?php $sg = ''; include "templates.php"; ?>
					</div> 
					<div style=" padding: 5px 0; text-align: center; color: 7f98a1;">
						Sponsored by <?php @include('images/img2.tif')?> | <?php @include('images/img1.tif')?>
					</div>
				</div>
			</div>
		</div>
		<!--footer end-->
		
</body>
</html>
