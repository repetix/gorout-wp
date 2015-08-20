jQuery(document).ready(function() {

    var colorOptions = {
        defaultColor: "#1FAE66",
        palettes: true
    };

    //jQuery(".goroutwp-color-field").wpColorPicker(colorOptions);
    
    //handle google api form submission
    jQuery("#googleapi input.button").on("click", function(){ 
        if(jQuery("#goroutwp_apikey").val() == ""){
            //if form fields check fails
            jQuery("#rusureok #confirm").attr("data-action", "closeme");
            jQuery("#rusureok p").html("Please enter the API key provided to you by Google. <strong>An API Key is required to use the font utility.</strong>");
            jQuery("#confirmation").html(jQuery("#rusureok").html());
            jQuery("#notice-overlay").fadeIn("slow");
            jQuery("#confirmation").fadeIn("slow");
            jQuery("#confirm").focus();
        } else {
            //load ajax spinner
            jQuery("#loadajax p").html("Validating your Google Developer API Key, one moment, please stand by...");
            jQuery("#confirmation").html(jQuery("#loadajax").html());
            jQuery("#notice-overlay").fadeIn("slow");
            jQuery("#confirmation").fadeIn("slow");
            //kill any remaining messages
            jQuery(".message").fadeOut("slow");
            //set the filename
            
            var data = {
        		action: 'goroutwp_key',
        		goroutwp_apikey: jQuery("#goroutwp_apikey").val()
        	};
        
        	jQuery.post(ajaxurl, data, function(response) {
        		jQuery("#response-div").html(response);
                var timerId = setTimeout(function() { 
                    jQuery("#notice-overlay").fadeOut("slow");
                    jQuery("#confirmation").fadeOut("slow");
                    jQuery("#loadajax p, #confirmation").html("");
                }, 1500);
        	});
            
        }
    });
    
    jQuery(document).find("img.menu_pto").remove();
    
});