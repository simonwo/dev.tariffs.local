function are_you_sure() {
	var r = confirm("Are you sure?");
	if (r == true) {
		return (true);
	} else {
		return (false);
	}
}

$(document).ready(function() {
	/*
	showing         = $("#showing");
	show_instead    = $("#show_instead");
	
	if ($.cookie('showing') == "Brexit") {
		showing.text("Showing Brexit");
		show_instead.text("Show Now instead");
	} else {
		showing.text("Showing Now");
		show_instead.text("Show Brexit instead");
	}

	$("#context_switcher").click(function() {
		showing         = $("#showing");
		show_instead    = $("#show_instead");
		
		if (showing.text() == "Showing Now") {
			document.cookie = "showing=Brexit";
		} else {
			document.cookie = "showing=Now";
		}
		document.location.reload();
	});
	*/

// Scripts to handle the selection of the measure origin sections
	$("#geographical_area_id_all").click(function() {
		$("#geographical_area_id_erga_omnes_content").show();
		$("#geographical_area_id_group_content").hide();
		$("#geographical_area_id_country_content").hide();
	});
	$("#geographical_area_id_group").click(function() {
		$("#geographical_area_id_erga_omnes_content").hide();
		$("#geographical_area_id_group_content").show();
		$("#geographical_area_id_country_content").hide();
	});
	$("#geographical_area_id_country").click(function() {
		$("#geographical_area_id_erga_omnes_content").hide();
		$("#geographical_area_id_group_content").hide();
		$("#geographical_area_id_country_content").show();
	});

});