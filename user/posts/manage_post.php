<?php
require_once('./../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `post_list` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<style>
	#upload-images{
		height:40em;
		width:100%;
		display:flex;
		flex-direction:column;
		align-items:center;
		justify-content:center;
		border: 2px dashed gray;
		position: running;
	}
</style>
<div class="container-fluid">
	<form action="" id="post-form">
		<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group mb-3">
			<label for="caption" class="control-label">Caption</label>
			<textarea rows="3" class="form-control form-control-sm rounded-0" id="caption" name="caption" required="required"><?= isset($caption) ? $caption : '' ?></textarea>
			<div id="upload-images" class="mt-4">
				<h4 class="font-weight-bolder" id="upload-text">Drop your Photos Here</h4>
				<div id="holder" class="w-100 px-3">
					<div id="template" class="row mt-2">
						<div class="col-auto">
							<span class="preview"><img src="data:," alt="" data-dz-thumbnail /></span>
						</div>
						<div class="col d-flex align-items-center">
							<p class="mb-0">
							<span class="lead" data-dz-name></span>
							(<span data-dz-size></span>)
							</p>
							<strong class="error text-danger" data-dz-errormessage></strong>
						</div>
						<div class="col-auto d-flex align-items-center">
							<div class="btn-group">
								<button data-dz-remove class="btn btn-light bg-gradient-light border btn-sm cancel" type="button">
								<i class="fas fa-times-circle"></i>
								<span>Cancel</span>
								</button>
							</div>
						</div>
					</div>
				</div>
				<button id='select-upload' class='btn btn-primary bg-gradient-primary rounded-0' type='button'>Upload Photos</button>
			</div>
		</div>
	</form>
</div>
<script>
	$(document).ready(function(){
		$('#post-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_post",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(typeof resp =='object' && resp.status == 'success'){
						location.href = "./?page=posts/view_post&id="+resp.aid
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body, .modal").scrollTop(0)
                            end_loader()
                    }else{
						alert_toast("An error occured",'error');
						end_loader();
                        console.log(resp)
					}
				}
			})
		})

		$('#uni_modal').on('shown.bs.modal', function(){
			// DropzoneJS Demo Code Start
			Dropzone.autoDiscover = false

			// Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
			var previewNode = document.querySelector("#template")
			previewNode.id = ""
			var previewTemplate = previewNode.parentNode.innerHTML
			previewNode.parentNode.removeChild(previewNode)

			var myDropzone = new Dropzone(document.querySelector('#upload-images'), { // Make the whole body a dropzone
			url: "/target-url", // Set the url
			thumbnailWidth: 80,
			thumbnailHeight: 80,
			parallelUploads: 20,
			previewTemplate: previewTemplate,
			autoQueue: false, // Make sure the files aren't queued until manually added
			previewsContainer: "#holder", // Define the container to display the previews
			clickable: "#select-upload" // Define the element that should be used as click trigger to select files.
			})

			myDropzone.on("addedfile", function(file) {
			// Hookup the start button
			var input = document.createElement('input')
			input.setAttribute('type','file')
			input.setAttribute('name',"img[]")
			input.classList.add("d-none");
			// input.files = file
			var data = new DataTransfer();
			data.items.add(file)
			input.files = data.files
			$(file.previewElement).append($(input))
			file.previewElement.querySelector(".cancel").onclick = function() { 
				if($('#holder .dz-image-preview').length <=0){
					$('#upload-text').removeClass('d-none')
					$('#select-upload').removeClass('d-none')
				}
			 }
			$('#upload-text').addClass('d-none')
			$('#select-upload').addClass('d-none')
			})

			// Update the total progress bar
			myDropzone.on("totaluploadprogress", function(progress) {
			document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
			})

			myDropzone.on("sending", function(file) {
			// Show the total progress bar when upload starts
			document.querySelector("#total-progress").style.opacity = "1"
			// And disable the start button
			file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
			})

			// Hide the total progress bar when nothing's uploading anymore
			myDropzone.on("queuecomplete", function(progress) {
			document.querySelector("#total-progress").style.opacity = "0"
			})
		})

	})
</script>