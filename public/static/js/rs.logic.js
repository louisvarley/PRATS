
function LitterSwitcher(){


	if(jQuery('#litter').val() == ''){	
		jQuery('#form-group-litter-s2').fadeIn();					
	}else{
		jQuery('#form-group-litter-s2').fadeOut();			
	}
	
}


rs.init("rats_logic",function(){
	
	
	
	/* Litter Changes */
	jQuery(document).ready(function(){
		LitterSwitcher();
		jQuery('#litter').change(function(){LitterSwitcher();})		
	});
})