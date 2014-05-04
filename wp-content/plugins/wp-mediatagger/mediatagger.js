function mdtg_submit(post_var_name, post_var_value, flag_no_submit) {
	document.mdtg_form.elements[post_var_name].value = post_var_value;
	
	if (typeof flag_no_submit == 'undefined') {
		document.mdtg_form.submit();
	}
}

function mdtg_toggle(source, flex_arg) {
	checkboxes = document.getElementsByName('mdtg_select[]');
	for(var i=0, n=checkboxes.length;i<n;i++) {
		checkboxes[i].checked = source.checked;
	}
	mdtg_manage_checkboxes(flex_arg);
}

function mdtg_manage_checkboxes(flex_arg) {
	var $n_selected = jQuery("input[name='mdtg_select[]']:checked").length;
	var $n_total = jQuery("input[name='mdtg_select[]']").length;
	
	update_count_label($n_selected);
	set_master_select($n_selected, $n_total);
	if (flex_arg == -1) {
		document.mdtg_form.submit();
	} else {
		manage_button_activation($n_selected, flex_arg);	// Tag - Add to List - Remove from list	
	}
}

/////////////////////////////////////////////////////////////////////////////////////:
// Sub functions
//
function update_count_label(n_selected){
	//var $n = jQuery("input[name='mdtg_select[]']:checked").length;
	jQuery('#mdtg_list_count').text(n_selected);	
}

function set_master_select(n_selected, n_total) {
	if (n_selected == n_total){
		document.getElementsByName('mdtg_select_master')[0].checked = true;
	} else {
		document.getElementsByName('mdtg_select_master')[0].checked = false;		
	}
}

function manage_button_activation(n_selected, custom_list_count){	// Tag - Add to List - Remove from list
	if (n_selected > 0) {
		document.getElementsByName("mdtg_submit_list")[0].disabled = false;
		document.getElementsByName("mdtg_submit_list")[1].disabled = false;
		document.getElementsByName("mdtg_submit_list")[2].disabled = false;
		//jQuery("input[name='mdtg_submit_list']").removeAttr("disabled");
	} else {
		document.getElementsByName("mdtg_submit_list")[0].disabled = true;
		document.getElementsByName("mdtg_submit_list")[1].disabled = true;
		document.getElementsByName("mdtg_submit_list")[2].disabled = true;
	}
	
	if (custom_list_count > 0) {
		document.getElementsByName("mdtg_submit_list")[3].disabled = false;	
	} else {
		document.getElementsByName("mdtg_submit_list")[3].disabled = true;			
	}
}