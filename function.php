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
