<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 

// Fetch module parameters
$include_js			= intval($params->get('include_js', 1));
$menutype			= $params->get('menutype', 'mainmenu');
$menu_alignment			= $params->get('menu_alignment', 'top_horizontal');
$sticky_menu			= intval($params->get('sticky_menu', 0));
$max_sub_menus			= intval($params->get('max_sub_menus', 2));
$sub_menu_horizontal_alignment	= $params->get('sub_menu_horizontal_alignment', 'item');
$sub_menu_overflow_right	= $params->get('sub_menu_overflow_right', 1);
$effect_size			= intval($params->get('effect_size', 1));
$effect_opacity			= intval($params->get('effect_opacity', 1));
$effect_link_opacity		= intval($params->get('effect_link_opacity', 1));
$transition_duration		= intval($params->get('transition_duration', 500));
$timeout_duration		= intval($params->get('timeout_duration', 100));
$submenu_x_offset		= intval($params->get('submenu_x_offset', 0));
$submenu_y_offset		= intval($params->get('submenu_y_offset', 0));
$active_menu_state		= intval($params->get('active_menu_state', 0));
$hover_menu_state		= intval($params->get('hover_menu_state', 0));
// If both submenu size and fade effect are off, use fade with zero duration time.
if($effect_size == 0 && $effect_opacity == 0)
{
	$effect_opacity = 1;
	$transition_duration = 0;
}

$rows = modPraiseMenuHelper::getMenuItemRows($menutype);
$root = modPraiseMenuHelper::buildMenu($rows);

echo "<div id=\"menuContainer\" class=\"menuContainer\">\n";

modPraiseMenuHelper::generateMenuItemHtml($root, $menu_alignment, $max_sub_menus, $active_menu_state);
echo "</div>\n";

// START OUTPUT
?>
<link href="http://localhost/praisit/templates/jp_praiseit_j1.5/html/mod_praisemenu/mod_praisemenu.css" rel="stylesheet" type="text/css" />
<?php
	// load mootools if enabled in the module config
	if($include_js)
	{
?>
		<script language="javascript" type="text/javascript" src="<?php echo JURI::base(true); ?>/modules/mod_praisemenu/mootools-release-1.11.js"></script>
<?php
	}
?>
<script language="javascript" type="text/javascript">
	var lastOpenMenu = null;
	var menuContainerZeroCoords;
	function clearTimer(menuItemText)
	{
		if(menuItemText.timerId != null)
		{
			clearTimeout(menuItemText.timerId);
			menuItemText.timerId = null;
		}
	}
	function menuItemTextMouseOver(menuItemText)
	{
		var parentMenuId;

		// Clear the close event on ourself.
		clearTimer(menuItemText);

		// Clear the close event on our parents
		var parentMenuItemText = menuItemText;
		while((parentMenuId = parentMenuItemText.attributes['parentMenuId'].value) != 0)
		{
			parentMenuItemText = $('menuItemText_' + parentMenuId);
			clearTimer(parentMenuItemText);
		}

		// Only open if it's closed.
		if(!menuItemText.isOpen)
		{
			openMenu(menuItemText);
		}
	}
	function menuItemTextMouseOut(menuItemText, forceClose)
	{
		// Only close if it's open.
		if(menuItemText.isOpen && (!<?php echo $sticky_menu ? 'true' : 'false'; ?> || forceClose))
		{
			// Make sure no children are open
			var menuContainer = $('menuContainer_' + menuItemText.attributes['menuId'].value);
			if(menuContainer)
			{
				var subMenuItemTexts = menuContainer.getElements('a');
				for(i = 0; i < subMenuItemTexts.length; i++)
				{
					if(subMenuItemTexts[i].isOpen)
					{
						return;
					}
				}
			}

			// Don't kick it off if it's already waiting to close.
			if(menuItemText.timerId == null)
			{
				menuItemText.timerId = setTimeout('closeMenu("' + menuItemText.id + '", ' + forceClose + ')', <?php echo $timeout_duration ?>);
			}
		}
	}
	function startEffects(menuContainer, start)
	{
		var i;
		for(i = 0; i < menuContainer.effects.length; i++)
		{
			menuContainer.effects[i].stop();
		}
		for(i = 0; i < menuContainer.effects.length; i++)
		{
			menuContainer.effects[i].start(start ? menuContainer.effects_start[i] : menuContainer.effects_end[i]);
		}
	}
	function openMenu(menuItemText)
	{
		var menuContainer = $('menuContainer_' + menuItemText.attributes['menuId'].value)

		menuItemText.isOpen = true;

		if(lastOpenMenu != null && menuItemText != lastOpenMenu)
		{
			menuItemTextMouseOut(lastOpenMenu, true);
		}
		lastOpenMenu = menuItemText;

		// Opening effect
		<?php if($effect_link_opacity == 1) { ?>
			menuItemText.effect('opacity').start(.3,1);
		<?php } ?>
		if(menuContainer)
		{
			startEffects(menuContainer, false);
		}
	}
	function closeMenu(menuItemTextId, forceClose)
	{
		var menuItemText = $(menuItemTextId)
		menuItemText.isOpen = false;
		var menuContainer = $('menuContainer_' + menuItemText.attributes['menuId'].value);

		// Closing effect
		<?php if($effect_link_opacity == 1) { ?>
			menuItemText.effect('opacity').set(1);
		<?php } ?>
		if(menuContainer)
		{
			startEffects(menuContainer, true);
		}

		// Close our parent
		var parentMenuItemText = $('menuItemText_' + menuItemText.attributes['parentMenuId'].value);
		if(parentMenuItemText)
		{
			menuItemTextMouseOut(parentMenuItemText, forceClose);
		}
	}
	function getNumber(text)
	{
		if(text)
		{
			text = text.replace(/[^0-9]/g, '');
		}
		else
		{
			text = 0;
		}

		return Number(text);
	}
	function getBorder(menuItemText, borderName, nodeCount)
	{
		var nodeStyle;
		var total = 0;
		var node;
	       
		node = menuItemText;
		while(nodeCount > 0)
		{
			if(document.defaultView)
			{
				nodeStyle = document.defaultView.getComputedStyle(node, '');
			}
			else
			{
				nodeStyle = node.currentStyle;
			}
			total += getNumber(eval('nodeStyle.border' + borderName + 'Width'));
			node = node.getParent();
			nodeCount--;
		}

		return total;
	}
	function setMenuCoords(menuItemText, menuContainer)
	{
		var subMenuContainer;
		var parentMenuContainerCoords;
		var submenuXOffset = <?php print $submenu_x_offset; ?>;
		var submenuYOffset = <?php print $submenu_y_offset; ?>;
		if(menuItemText != null)
		{
                        // This is a fix for IE6.  Without this, the Horizontal All submenus show up to the right of the main menu (off the screen).
                        var parentMenuContainer = menuItemText.getParent().getParent().getParent();
                        parentMenuContainer.setStyle('opacity', '1');

			var menuItemTextCoords = menuItemText.getCoordinates();
			var menuContainerCoords = menuContainer.getCoordinates();
			parentMenuContainerCoords = menuItemText.getParent().getParent().getParent().getCoordinates();

			// This is a fix for IE7.  The zoom=1 style seems to change some styles, so we want to do it up front.
			menuContainerWidth = menuContainer.getStyle('width');
			menuContainer.setStyle('opacity', '1');
			menuContainer.setStyle('width', menuContainerWidth);

			if(menuContainerCoords)
			{
				// Line up with our parent
				if(<?php echo $sub_menu_horizontal_alignment == 'container' ? 'true' : 'false' ?>)
				{
					menuContainer.style.left = (parentMenuContainerCoords.left - menuContainerZeroCoords.left + submenuXOffset) + 'px';
					menuContainer.style.top = (parentMenuContainerCoords.bottom - menuContainerZeroCoords.top + submenuYOffset) + 'px';
				}
				else if(menuItemText.className == "verticalMenuItemText")
				{
					menuContainer.style.left = (parentMenuContainerCoords.right - menuContainerZeroCoords.left + submenuXOffset) + 'px';
					menuContainer.style.top = (menuItemTextCoords.top - menuContainerZeroCoords.top + submenuYOffset) + 'px';
				}
				else
				{
					if(<?php print $sub_menu_overflow_right ? 'false' : 'true'; ?> && (menuItemTextCoords.left - menuContainerZeroCoords.left + menuContainerCoords.width) > menuContainerZeroCoords.width)
					{
						// If we go farther right than our container, line up with the right side.
						menuContainer.style.left = (menuItemTextCoords.left + menuItemTextCoords.width - menuContainerCoords.width + submenuXOffset) + 'px';
					}
					else
					{
						menuContainer.style.left = (menuItemTextCoords.left - menuContainerZeroCoords.left + submenuXOffset) + 'px';
					}
					menuContainer.style.top = (parentMenuContainerCoords.bottom - menuContainerZeroCoords.top + submenuYOffset) + 'px';
				}

				// Make sure we are as wide as our parent
				if(menuContainerCoords.width < menuItemTextCoords.width && menuItemText.className == 'horizontalMenuItemText')
				{
					menuContainer.style.width = (menuItemTextCoords.width + getBorder(menuItemText, 'Left', 1) + getBorder(menuItemText, 'Right', 3) - getBorder(menuContainer, 'Left', 1) - getBorder(menuContainer, 'Right', 1)) + 'px';
				}
			}
		}

		// Process all items in this menu container
		var menuItemTexts = menuContainer.getElements('a');
		menuItemTexts.each(function(subMenuItemText){
			subMenuContainer = $('menuContainer_' + subMenuItemText.attributes['menuId'].value);
			if(subMenuContainer != null)
			{
				setMenuCoords(subMenuItemText, subMenuContainer);
			}
		});
	}
	window.extend({
		unshiftEvent: function(type, fn){
			this.$events = this.$events || {};
			this.$events[type] = this.$events[type] || {'keys': [], 'values': []};
			if (this.$events[type].keys.contains(fn)) return this;
			//this.$events[type].keys.push(fn);
			this.$events[type].keys.unshift(fn);
			var realType = type;
			var custom = Element.Events[type];
			if (custom){
				if (custom.add) custom.add.call(this, fn);
				if (custom.map) fn = custom.map;
				if (custom.type) realType = custom.type;
			}
			if (!this.addEventListener) fn = fn.create({'bind': this, 'event': true});
			//this.$events[type].values.push(fn);
			this.$events[type].values.unshift(fn);
			return (Element.NativeEvents.contains(realType)) ? this.addListener(realType, fn) : this;
		}
	});
	window.addEvent('resize', function() {
		var menuContainer;
		menuContainer = $('menuContainer_0');
		// Make sure all submenu containers are displayed (otherwise dimensions don't work properly).
		var menuContainers = $$('div.horizontalMenuContainer,div.verticalMenuContainer,div.sub-horizontalMenuContainer,div.sub-verticalMenuContainer,div.sub-singleMenuContainer');
		menuContainers.each(function(menuContainer) {
			if(menuContainer.id != 'menuContainer_0')
			{
				menuContainer.setStyle('display', '');
			}
		});
		// Line up all the submenus
		setMenuCoords(null, menuContainer);
		// Set display to none if we need to.
		<?php if($effect_opacity == 1) { ?>
			menuContainers.each(function(menuContainer) {
				if(menuContainer.id != 'menuContainer_0')
				{
					menuContainer.style.opacity = '0';
					menuContainer.style.display = 'none';
				}
			});
		<?php } ?>
	});
	window.unshiftEvent('domready', function() {
		var menuItemTexts = $$('a.horizontalMenuItemText,a.verticalMenuItemText,a.singleMenuItemText');
		var menuItemTextWidth;
		var menuContainer;

		<?php if($hover_menu_state == 1) { ?>
			$$('.topMenuItem').each(function(menuItem) {
				menuItem.addEvent('mouseenter', function() { this.addClass('hover'); });
				menuItem.addEvent('mouseleave', function() { this.removeClass('hover'); });
			});
		<?php } ?>

		menuContainer = $('menuContainer_0');
		// Get the coordinates before switching to relative.
		menuContainerZeroCoords = menuContainer.getCoordinates();
		menuContainer.style.position = 'relative';

		menuItemTexts.each(function(menuItemText) {
			// Add mouse events
			menuItemText.addEvent('mouseover', function(event) {
				menuItemTextMouseOver(this);
			});
			menuItemText.addEvent('mouseout', function(event) {
				menuItemTextMouseOut(this, false);
			});
			menuItemText.isOpen = false;

			// This is a fix for IE6.  Setting zoom=1 is making the anchor tag with display: block extend the width of the screen.  Reset it below.
			var menuItemTextWidth = menuItemText.getStyle('width');

			// This is a fix for IE7.  The zoom=1 style seems to change some styles, so we want to do it up front.
			menuItemText.setStyle('opacity', '1');
			menuItemText.setStyle('width', menuItemTextWidth);
		});
		setMenuCoords(null, menuContainer);
		var menuContainers = $$('div.horizontalMenuContainer,div.verticalMenuContainer,div.sub-horizontalMenuContainer,div.sub-verticalMenuContainer,div.sub-singleMenuContainer');
		var menuContainerCoords;
		menuContainers.each(function(menuContainer) {
			if(menuContainer.id != 'menuContainer_0')
			{
				menuContainerCoords = menuContainer.getCoordinates();

				// Add our effects
				menuContainer.effects = new Array();
				menuContainer.effects_start = new Array();
				menuContainer.effects_end = new Array();
				<?php if($effect_size == 1) { ?>
					var effect_end_value;
					if(menuContainer.className == 'verticalMenuContainer' || menuContainer.className == 'sub-verticalMenuContainer' || menuContainer.className == 'sub-singleMenuContainer' || <?php echo $menu_alignment == 'all_horizontal' ? 'true' : 'false' ?>)
					{
						menuContainer.dimension = 'height';
						effect_end_value = menuContainerCoords.height;
					}
					else
					{
						menuContainer.dimension = 'width';
						effect_end_value = menuContainerCoords.width;
					}
					menuContainer.effects[menuContainer.effects.length] = new Fx.Style(menuContainer.id, menuContainer.dimension, {duration:<?php echo $transition_duration ?>});
					menuContainer.effects_start[menuContainer.effects_start.length] = 0;
					menuContainer.effects_end[menuContainer.effects_end.length] = effect_end_value;

					eval('menuContainer.style.' + menuContainer.dimension + '= \'0px\'');
				<?php
				}
				if($effect_opacity == 1) { ?>
					menuContainer.effects[menuContainer.effects.length] = new Fx.Style(menuContainer.id, 'opacity', {duration:<?php echo $transition_duration ?>});
					// Links will still be active with opacity=0.  This makes sure they aren't clickable when the menu is closed.
					menuContainer.effects[menuContainer.effects.length - 1].addEvent('onStart', function(event) {
						if(this.element.style.opacity != 1)
						{
							this.element.style.display = '';
						}
					});
					menuContainer.effects[menuContainer.effects.length - 1].addEvent('onComplete', function(event) {
						if(this.element.style.opacity != 1)
						{
							this.element.style.display = 'none';
						}
					});
					menuContainer.effects_start[menuContainer.effects_start.length] = 0;
					menuContainer.effects_end[menuContainer.effects_end.length] = 1;

					menuContainer.style.opacity = '0';
					menuContainer.style.display = 'none';
				<?php } ?>
			}
			else
			{
				menuContainer.setStyle('opacity', '1');
			}
		});

		// We have opacity turned off in the style and turn it on here so the user doesn't see menus moving about when the page loads.
		$('menuContainer').setStyle('opacity', '1');
	});
        // This just avoids the "this.$events[type].keys is undefined" error when used with FPSS
        window.addEvent('load', function() { });
        window.addEvent('unload', function() { });
</script>
