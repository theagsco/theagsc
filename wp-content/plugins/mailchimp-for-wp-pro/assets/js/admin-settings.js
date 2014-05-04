(function($) {

	$('#mc4wp-admin input[name$="[double_optin]"]').change(function() {
		if($(this).val() == 0) {
			$("#mc4wp-send-welcome").removeClass('hidden').find(':input').removeAttr('disabled');
		} else {
			$("#mc4wp-send-welcome").addClass('hidden').find(':input').attr('disabled', 'disabled').attr('checked', false);
		}
	});

	$('#mc4wp-admin input[name="mc4wp_form[update_existing]"]').change(function() {
		if($(this).val() == 1) {
			$("#mc4wp-replace-interests").removeClass('hidden').find(':input').removeAttr('disabled');
		} else {
			$("#mc4wp-replace-interests").addClass('hidden').find(':input').attr('disabled', 'disabled').attr('checked', false);
		}
	});

	$("#mc4wp-admin select[name='mc4wp_form[css]']").change(function() {
		$("#mc4wp-custom-color").toggle(($(this).val() == 'custom-color'));
	});

	// init
	$('input.color-field').wpColorPicker();

	$("#form-toggle-license").submit(function() {
		$submit = $(this).find('input[type="submit"]');
		var newValue = ($submit.val() == 'Activate License') ? 'Activating..' : 'Deactivating..';
		$submit.val(newValue).prop('disabled', true);
	})

})(jQuery)