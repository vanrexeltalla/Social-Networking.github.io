<?php 
require_once('../../config.php');
$meta_qry = $conn->query("SELECT * FROM `member_meta` where member_id= '{$_settings->userdata('id')}' ");
if($meta_qry->num_rows > 0){
    while($row = $meta_qry->fetch_assoc()){
        ${$row['meta_field']} = $row['meta_value'];
    }
}
?>
<div class="container-fluid">
    <form action="" id="manage-personal-info">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label for="gender" class="control-label">Gender</label>
                <select class="form-select form-select-sm rounded-0" id="gender" name="gender" required="required">
                    <option <?= isset($gender) && $gender == 'Male' ? "selected" : "" ?>>Male</option>
                    <option <?= isset($gender) && $gender == 'Female' ? "selected" : "" ?>>Female</option>
                </select>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label for="dob" class="control-label">Birthday</label>
                <input type="date" class="form-control form-control-sm rounded-0" id="dob" name="dob" required="required" value="<?= isset($dob) ? date("Y-m-d", strtotime($dob)) : '' ?>">
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label for="contact" class="control-label">Contact #</label>
                <input type="text" class="form-control form-control-sm rounded-0" id="contact" name="contact" required="required" value="<?= isset($contact) ? $contact : '' ?>">
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="address" class="control-label">Address</label>
                <textarea rows="3" class="form-control form-control-sm rounded-0" id="address" name="address" required="required"><?= isset($address) ? $address : "" ?></textarea>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label for="relation_status" class="control-label">Relationship Status</label>
                <select class="form-select form-select-sm rounded-0" id="relation_status" name="relation_status" required="required">
                    <option <?= isset($relation_status) && $relation_status == 'Single' ? "selected" : "" ?>>Single</option>
                    <option <?= isset($relation_status) && $relation_status == 'In-Relationship' ? "selected" : "" ?>>In-Relationship</option>
                    <option <?= isset($relation_status) && $relation_status == 'Married' ? "selected" : "" ?>>Married</option>
                </select>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="studied_at" class="control-label">Studied/Studying At</label>
                <textarea rows="2" class="form-control form-control-sm rounded-0" id="studied_at" name="studied_at"><?= isset($studied_at) ? $studied_at : "" ?></textarea>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="working_at" class="control-label">Currently Working At</label>
                <textarea rows="2" class="form-control form-control-sm rounded-0" id="working_at" name="working_at"><?= isset($working_at) ? $working_at : "" ?></textarea>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="about_me" class="control-label">About Me</label>
                <textarea rows="4" class="form-control form-control-sm rounded-0" id="about_me" name="about_me"><?= isset($about_me) ? $about_me : "" ?></textarea>
            </div>
        </div>
    </form>
</div>
<script>
    $(function(){
        $('#manage-personal-info').submit(function(e){
            e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=update_personal_info",
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
						location.reload()
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
    })
</script>