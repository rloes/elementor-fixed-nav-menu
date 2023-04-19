# elementor-fixed-nav-menu

This repository provides a fix for the elementor pro nav menu sub-indicator bug:
- [#21757](https://github.com/elementor/elementor/issues/21757)
- [#20859](https://github.com/elementor/elementor/issues/20859)

Elementors Nav Menu Widget has a bug, where a click on the sub-indicator icons only opens a menu but cannot close it.

A behaviour where a click on the name opens its link and a click on the sub indicator toggles the sub menu is currently not possible.

Therefore you can use this fix.

## Fixes

The fix adds minimal changes to the `itemClick` function of the smartmenus.js libary.

- First of all, clicks on the sub-indicator should be detected by smartmenus. But because Elementor sets svg to the `subIndicatorText` option of smartmenus, clicks often go undetected
```diff
-- var subArrowClicked = $(e.target).is("span.sub-arrow"),
++ var subArrowClicked = $(e.target).is("span.sub-arrow") || $(e.target).is("span.sub-arrow *"),				  
```

- Second, a click on the link always opens the submenu when its closed. I wanted the behaviour that a click on the link opens the link and only clicks on the sub indicator icon open/closes the sub-menu. Therefore I have added an option `skipCollapsible`. If set to true, a click on the link will always activate it.

```diff
++ if (!this.opts.skipCollapsible || subArrowClicked) {
			// try to activate the item and show the sub
			this.itemActivate($a);
			// if "itemActivate" showed the sub, prevent the click so that the link is not loaded
			// if it couldn't show it, then the sub menus are disabled with an !important declaration (e.g. via mobile styles) so let the link get loaded
			 if ($sub.is(":visible")) {
				this.focusActivated = true;
				return false;
			}
++		}				  
```

## Options

In case you want to set the described collapsible behavior. You have to change the data on the smartmenu `<ul>` with jQuery, like this:

```js
jQuery(document).ready(function(){
	var $ul = jQuery('ul:first');
	$ul.data("smartmenus").opts.skipCollapsible = true;
});
```
This option will achieve the following behavior:
![2023-04-20-00-07-46](https://user-images.githubusercontent.com/92471929/233210705-f430c1a4-20c7-45f1-b362-3f4799fb59f1.gif)

## Installation

### Install per Plugin

- Go to releases and download the latest version
- in your WordPress-Dashboard go to Plugins and Install
- Upload and Install the downloaded .zip file

### Install per Code-Snippet or function.php

Add following Snippet to your function.php or use a Plugin/Tool like Code Snippets or Elementor Custom Code.

```php

add_action('wp_footer', function(){ ?>
	<script>
		jQuery.extend(jQuery.SmartMenus.prototype, {itemClick: function (e) {
		  var $ = jQuery;	
		  var $a = $(e.currentTarget);
		  if (!this.handleItemEvents($a)) {
			  return;
		  }
		  if (
			  this.$touchScrollingSub &&
			  this.$touchScrollingSub[0] == $a.closest("ul")[0]
		  ) {
			  this.$touchScrollingSub = null;
			  e.stopPropagation();
			  return false;
		  }
		  if (this.$root.triggerHandler("click.smapi", $a[0]) === false) {
			  return false;
		  }
		  var subArrowClicked =
				  $(e.target).is("span.sub-arrow") ||
				  $(e.target).is("span.sub-arrow *"),
			  $sub = $a.dataSM("sub"),
			  firstLevelSub = $sub ? $sub.dataSM("level") == 2 : false;
		  // if the sub is not visible
		  if ($sub && !$sub.is(":visible")) {
			  if (this.opts.showOnClick && firstLevelSub) {
				  this.clickActivated = true;
			  }
			  if (!this.opts.skipCollapsible || subArrowClicked) {
				  // try to activate the item and show the sub
				  this.itemActivate($a);
				  // if "itemActivate" showed the sub, prevent the click so that the link is not loaded
				  // if it couldn't show it, then the sub menus are disabled with an !important declaration (e.g. via mobile styles) so let the link get loaded
				  if ($sub.is(":visible")) {
					  this.focusActivated = true;
					  return false;
				  }
			  }
		  } else if (this.isCollapsible() && subArrowClicked) {
			  this.itemActivate($a);
			  this.menuHide($sub);
			  return false;
		  }
		  if (
			  (this.opts.showOnClick && firstLevelSub) ||
			  $a.hasClass("disabled") ||
			  this.$root.triggerHandler("select.smapi", $a[0]) === false
		  ){
			  return false;
		  }
		}
		}
		)
	</script>
<?php }, 100);

```

Just make sure, it loads in the footer, after the smartmenus.js file.
