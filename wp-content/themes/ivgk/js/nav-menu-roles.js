(function($) {
	
	$('body').on('change', 'input[class=bulk-select-switcher]', function () {
		var thisData = $("input[class=bulk-select-switcher]").is(":checked");
		if(thisData)
		{
			$("input[class*=selectedMenu-this-item]").prop("checked", true);
		}else{
			$("input[class*=selectedMenu-not-item]").prop("checked", true);
		}
    });
	
    $('.nav_menu_logged_in_out_field').each(function(i) {
        var $field = $(this);
		//alert($field.find('input.nav-menu-logged-in-out:checked').val());
        if ($field.find('input.nav-menu-logged-in-out:checked').val() === 'in') {
            $field.next('.nav_menu_role_field').show()
        } else {
            $field.next('.nav_menu_role_field').hide()
        }
    });
    $('#menu-to-edit').on('change', 'input.nav-menu-logged-in-out', function() {
        if ($(this).val() === 'in') {
            $(this).parentsUntil('.nav_menu_logged_in_out').next('.nav_menu_role_field').slideDown()
        } else {
            $(this).parentsUntil('.nav_menu_logged_in_out').next('.nav_menu_role_field').slideUp()
        }
    })
})(jQuery)