
function LitterSwitcher(){

	if(jQuery('#litter').val() == ''){
				
	
				
	}else{
		
		
						
		jQuery('#form-row-country').hide();
		jQuery('#form-row-date').hide();
		jQuery('#form-row-dam').hide();	
		jQuery('#form-row-buck').hide();			
		
	}
	
	
}


rs.init("rats_logic",function(){
	
	LitterSwitcher();
	
	/* Max Length */
	jQuery(document).ready(function(){
				
		jQuery('#litter').change(function(){
			
			
			LitterSwitcher();
		
			
		})
			
			
	});
})