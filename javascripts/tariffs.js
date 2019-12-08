
function are_you_sure() {
	var r = confirm("Are you sure?");
	if (r == true) {
		return (true);
	} else {
		return (false);
	}
}

$(document).ready(function() {
	my_url		= window.location.href;
	common_url	= my_url.replace("dev.", 			"common.");
	common_url	= common_url.replace("staging.",	"common.");
	common_url	= common_url.replace("eu.",			"common.");
	common_url	= common_url.replace("fta.",		"common.");
	common_url	= common_url.replace("build.",		"common.");
	common_url	= common_url.replace("load.",		"common.");
	common_url	= common_url.replace("national.",	"common.");

	dev_url			= common_url.replace("common.", "dev.");
	staging_url 	= common_url.replace("common.", "staging.");
	eu_url			= common_url.replace("common.", "eu.");
	fta_url			= common_url.replace("common.", "fta.");
	build_url		= common_url.replace("common.", "build.");
	load_url		= common_url.replace("common.", "load.");
	national_url	= common_url.replace("common.", "national.");

	$("#context_switcher_dev").attr("href", 		dev_url);
	$("#context_switcher_staging").attr("href", 	staging_url);
	$("#context_switcher_eu").attr("href", 			eu_url);
	$("#context_switcher_fta").attr("href", 		fta_url);
	$("#context_switcher_build").attr("href", 		build_url);
	$("#context_switcher_load").attr("href", 		load_url);
	$("#context_switcher_national").attr("href",	national_url);


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
	$("#relation_type_nm").click(function() {
		$("#coefficient").val("1.00000");
		/*
		$("#geographical_area_id_erga_omnes_content").hide();
		$("#geographical_area_id_group_content").hide();
		$("#geographical_area_id_country_content").show();
		*/
	});

	$("#main_definition_period").click(function() {
		main_txt = $("#main_definition_period option:selected").text()
		main_val = $("#main_definition_period option:selected").val()

		$("#sub_definition_period > option").each(function() {
			sub_val = $(this).val();
			sub_txt = $(this).text();
			if (main_txt == sub_txt) {
				$("#sub_definition_period").val(sub_val);
			}
		});
	});


	$("#sub_definition_period").click(function() {
		sub_txt = $("#sub_definition_period option:selected").text()
		sub_val = $("#sub_definition_period option:selected").val()

		$("#main_definition_period > option").each(function() {
			main_val = $(this).val();
			main_txt = $(this).text();
			if (sub_txt == main_txt) {
				$("#main_definition_period").val(main_val);
			}
		});
	});

	
	$("#radioID2").click(function() {
		$("div#cross-check-rejection-reason").css("display", "block !important");
	});


	$("#quota_order_number_id").keyup(function() {
		txt = $("#quota_order_number_id").val();
		var ln = txt.length;
		if (ln == 6) {
			if (txt.substring(0, 3) == "094") {
				$("#quota_fulfilment_method").text("This is a licensed quota");
			} else {
				$("#quota_fulfilment_method").text("This is a first come first served (FCFS) quota");
			}
		} else {
			$("#quota_fulfilment_method").text("");
		}
	});

	

});