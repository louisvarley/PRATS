

rs.init("image_upload_button", function(){

	/* Handle Clicked Upload */
	jQuery('.form-image').click(function(){	
		var input = jQuery(this).next();
		jQuery(input).click()
		return false;
	});
	
	/* Handle Image Upload Ajax */
	jQuery('.image-upload').ajaxfileupload({
		action: '/api/blobs/upload',
		valid_extensions : ['jpg','png'],
		onComplete: function(data) {
	
			if(data.code == 0){

				url = '/blob/' + data['response']['blobId'] + '.jpg';
				jQuery('.preview-image').find('img').attr('src',url)
				jQuery('.image-id').val(data['response']['blobId']);
					
			}else{
				rs.throwSuccess("Error...",data.error);
			}

		},
		onStart: function() {
			if(rs.isMobile()){
				alert("Uploading image from camera");
			}else{
			rs.throwSuccess("Uploading...","Started Upload...");
			}
			
		},
		onCancel: function() {
			console.log('no file selected');
		}
	});		
	
	/* Handle Delete Clicked*/
	jQuery('#purchase_images').find('.form-image-delete').click(function() {
	
		var blobId = jQuery(this).data("id");
		var purchaseId = jQuery('#id').val();

		jQuery.ajax({
			url: '/api/purchases/purchaseImage?blobId=' + blobId + '&purchaseId=' + purchaseId,
			cache: false,
			contentType: false,
			processData: false,
			method: 'DELETE',
			type: 'DELETE',
			success: function(data){
				
				jQuery('.form-image-' + blobId).fadeOut();
				return false;
			},
			fail: function(data){
				rs.throwSuccess("Error...", data['response']['error']);
			}
		});

	});
	
	/* Handle Preview Clicked*/
	jQuery('#purchase_images').find('.form-image-view').click(function() {
		
		var blobId = jQuery(this).data("id");
		
		window.open("/blob/" + blobId + '.jpg'); 
	});		
	
})