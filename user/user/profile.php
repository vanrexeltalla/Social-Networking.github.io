<?php 
if(!isset($_GET['user_id']) || (isset($_GET['user_id']) && $_GET['user_id'] == $_settings->userdata('id'))){
    $user = $conn->query("SELECT *, concat(firstname, ' ', coalesce(concat(middlename,' '),''),lastname) as `name` FROM member_list where id ='".$_settings->userdata('id')."'");
    foreach($user->fetch_array() as $k =>$v){
        $$k = $v;
    }
    $meta_qry = $conn->query("SELECT * FROM `member_meta` where member_id= '{$_settings->userdata('id')}' ");
    if($meta_qry->num_rows > 0){
        while($row = $meta_qry->fetch_assoc()){
            ${$row['meta_field']} = $row['meta_value'];
        }
    }
}else{
    $user = $conn->query("SELECT *, concat(firstname, ' ', coalesce(concat(middlename,' '),''),lastname) as `name` FROM member_list where id ='".$_GET['user_id']."'");
    foreach($user->fetch_array() as $k =>$v){
        $$k = $v;
    }
    $meta_qry = $conn->query("SELECT * FROM `member_meta` where member_id= '{$_GET['user_id']}' ");
    if($meta_qry->num_rows > 0){
        while($row = $meta_qry->fetch_assoc()){
            ${$row['meta_field']} = $row['meta_value'];
        }
    }
    $request = $conn->query("SELECT * FROM `request_list` where member_id = '{$_settings->userdata('id')}' and ask_member_id = '{$id}'");
    if($request->num_rows >0){
        $res = $request->fetch_array();
        $request_status = $res['status'];
    }
    $requested_qry = $conn->query("SELECT * FROM `request_list` where ask_member_id = '{$_settings->userdata('id')}' and member_id = '{$id}'");
    if($requested_qry->num_rows >0){
        $res = $requested_qry->fetch_array();
        $requested = $res;
    }
}
?>
<style>
	#profile-avatar{
      height: 8em;
      width: 8em !important;
      object-fit: cover;
      object-position: center;
    }
    .post-holder{
        width:100%;
        height:20em;
    }
    .post-img{
        width:100%;
        height:100%;
        object-fit:cover;
        object-position:center center;
        transition:transform .3s ease-in-out;
    }
    .post-item:hover .post-img{
        transform:scale(1.2);
    }
</style>
<div class="mx-0 py-5 px-3 mx-ns-4 bg-gradient-purple shadow blur d-flex w-100 justify-content-center align-items-center flex-column">
	<img src="<?= validate_image(isset($avatar) ? $avatar : '') ?>" alt="" class="img-thumbnail rounded-circle p-0" id="profile-avatar">
    <h3 class="text-center font-weight-bolder"><?= isset($name) ? $name : '' ?></h3>
    <div class="text-weight-500 font-weight-light"><?= isset($email) ? $email : '' ?></div>
</div>
<div class="row justify-content-center" style="margin-top:-2em;">
	<div class="col-lg-10 col-md-11 col-sm-12 col-xs-12">
        <div class="card rounded-0 shadow">
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row mb-3">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <?php if($id != $_settings->userdata('id')): ?>
                                    <div class="row mb-3 justify-content-end">
                                        <div class="col-lg-lg-6 col-md-6 col-lg-12 col-sm-12 text-right">
                                            <?php if(isset($request_status) && $request_status == 0): ?>
                                            <button class="btn btn-light bg-gradient-light border btn-sm rounded-0" type="button" disabled><i class="fa fa-user-clock"></i> Pending Friend Request</button>
                                            <?php elseif(isset($requested) && $requested['status'] == 0): ?>
                                            <button class="btn btn-primary bg-gradient-primary btn-sm rounded-0" type="button" data-request-id='<?= $requested['id'] ?>' id="confirm_request"><i class="fa fa-check"></i> Confirm Friend Request</button>
                                            <button class="btn btn-danger bg-gradient-danger btn-sm rounded-0" type="button" data-request-id='<?= $requested['id'] ?>' id="decline_request"><i class="fa fa-user-minus"></i> Decline</button>
                                            <?php elseif((isset($request_status) && $request_status == 1) || (isset($requested['status']) && $requested['status'] == 1)): ?>
                                            <button class="btn btn-light bg-gradient-light border btn-sm rounded-0" type="button" disabled><i class="fa fa-user-check"></i> Friend</button>
                                            <?php elseif(isset($requested) && $requested['status'] == 3): ?>
                                            <button class="btn btn-primary bg-gradient-primary btn-sm rounded-0" type="button" data-request-id='<?= $requested['id'] ?>' id="confirm_request"><i class="fa fa-check"></i> Reconsider Friend Request</button>
                                            <?php elseif(isset($request_status) && $request_status == 3): ?>
                                            <button class="btn btn-danger bg-gradient-danger border btn-sm rounded-0" type="button" disabled><i class="fa fa-user-minus"></i> Request has been Declined</button>
                                            <?php else: ?>
                                            <button class="btn btn-primary bg-gradient-primary btn-sm rounded-0" type="button" id="add_friend"><i class="fa fa-user-plus"></i> Send Friend Request</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <fieldset class="border">
                                <div class="d-flex w-100 justify-content-between">
                                    <legend class="w-auto px-3 fw-bolder">Personal Information</legend>
                                    <?php if($id == $_settings->userdata('id')): ?>
                                    <button class="btn btn-outline-primary rounded-0 btn-sm py-1 h-auto" type="button" id="edit_personal_information"><i class="fa fa-edit"></i></button>
                                    <?php endif; ?>
                                </div>
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="co-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <label for="">Gender</label>
                                            <div class="pl-3"><?= isset($gender) ? $gender : "N/A" ?></div>
                                        </div>
                                        <div class="co-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <label for="">Birthday</label>
                                            <div class="pl-3"><?= isset($dob) ? date("F d, Y", strtotime($dob)) : "N/A" ?></div>
                                        </div>
                                        <div class="co-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <label for="">Contact #</label>
                                            <div class="pl-3"><?= isset($contact) ? $contact : "N/A" ?></div>
                                        </div>
                                        <div class="co-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <label for="">Relationship Status</label>
                                            <div class="pl-3"><?= isset($relation_status) ? $relation_status : "N/A" ?></div>
                                        </div>
                                        <div class="co-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="">Address</label>
                                            <div class="pl-3"><?= isset($address) ? $address : "N/A" ?></div>
                                        </div>
                                        <div class="co-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="">Studying/Studied at</label>
                                            <div class="pl-3"><?= isset($studied_at) ? $studied_at : "N/A" ?></div>
                                        </div>
                                        <div class="co-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="">Working at</label>
                                            <div class="pl-3"><?= isset($working_at) ? $working_at : "N/A" ?></div>
                                        </div>
                                        <div class="co-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label for="">About Me</label>
                                            <div class="pl-3"><?= isset($about_me) ? $about_me : "N/A" ?></div>
                                        </div>
                                        <div class="co-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <label for="">Date Joined</label>
                                            <div class="pl-3"><?= date("F d, Y", strtotime($_settings->userdata('date_created')))?></div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <?php 
                            $qry = $conn->query("SELECT p.*, concat(m.firstname, ' ', coalesce(concat(m.middlename,' '),''),m.lastname) as `name`, m.avatar, COALESCE((SELECT count(member_id) FROM `like_list` where post_id = p.id),0) as `likes`, COALESCE((SELECT count(member_id) FROM `comment_list` where post_id = p.id),0) as `comments` FROM post_list p inner join `member_list` m on p.member_id = m.id where p.member_id = '{$id}' order by unix_timestamp(p.date_updated) desc");
                            while($row = $qry->fetch_assoc()):
                            $files = array();
                            $fopen = scandir(base_app.$row['upload_path']);
                            foreach($fopen as $fname){
                                if(in_array($fname,array('.','..')))
                                  continue;
                                $files[]= validate_image($row['upload_path'].$fname);
                            }
                        ?>
                        <a href="./?page=posts/view_post&id=<?= $row['id'] ?>" class="card rounded-0 shadow col-lg-4 col-md-6 col-sm-12 col-xs-12 post-item px-0 text-decoration-none text-reset">
                            <div class="post-holder overflow-hidden">
                                <img src="<?= isset($files[0]) ? $files[0] : validate_image('') ?>" class="card-img-top post-img" alt="...">
                            </div>
                            <div class="card-body">
                                <div class="card-description w-100 truncate-1"><?= $row['caption'] ?></div>
                                <div>
                                    <div class="like_post" data-id="<?= $row['id'] ?>"><i class="fa fa-heart text-danger"></i> <?= format_num($row['likes']) ?> Likes</div>
                                    <div class="post_comments" data-id="<?= $row['id'] ?>"><i class="far fa-comment"></i> <?= format_num($row['comments']) ?> Comments</div>
                                </div>
                            </div>
                        </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#edit_personal_information').click(function(){
            uni_modal("<i class='fa fa-id-card'></i> Update Personal Information", "user/manage_personal_info.php", 'modal-lg')
        })
        $('#add_friend').click(function(){
            start_loader()
            $.ajax({
                url:_base_url_+"classes/Master.php?f=add_friend",
                method:'POST',
                data:{ask_member_id:'<?= isset($id) ? $id : '' ?>'},
                dataType:'json',
                error:(err)=>{
                    console.log(err)
                    alert('Friend Request Failed due to some reasons.')
                },
                success:function(resp){
                    if(resp.status =='success'){
                        location.reload()
                    }else if(!!resp.msg){
                    alert(resp.msg)
                    }else{
                    console.log(resp)
                    alert('Friend Request Failed due to some reasons.')
                    }
                    end_loader()
                }
            })
        })
        $('#confirm_request').click(function(){
            start_loader()
            $.ajax({
                url:_base_url_+"classes/Master.php?f=confirm_request",
                method:'POST',
                data:{request_id:$('#confirm_request').attr('data-request-id')},
                dataType:'json',
                error:(err)=>{
                    console.log(err)
                    alert('Friend Request Failed due to some reasons.')
                    end_loader()
                },
                success:function(resp){
                    if(resp.status =='success'){
                        location.reload()
                    }else if(!!resp.msg){
                    alert(resp.msg)
                    }else{
                    console.log(resp)
                    alert('Friend Request Failed due to some reasons.')
                    }
                    end_loader()
                }
            })
        })
        $('#decline_request').click(function(){
            start_loader()
            $.ajax({
                url:_base_url_+"classes/Master.php?f=decline_request",
                method:'POST',
                data:{request_id:$('#decline_request').attr('data-request-id')},
                dataType:'json',
                error:(err)=>{
                    console.log(err)
                    alert('Friend Request Failed due to some reasons.')
                    end_loader()
                },
                success:function(resp){
                    if(resp.status =='success'){
                        location.reload()
                    }else if(!!resp.msg){
                    alert(resp.msg)
                    }else{
                    console.log(resp)
                    alert('Friend Request Failed due to some reasons.')
                    }
                    end_loader()
                }
            })
        })
    })
</script>