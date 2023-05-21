
<style>
    .request-img{
    width:5em;
    height:5em;
    object-fit:cover;
    object-position:center center;
    }
</style>
<div class="mx-0 py-5 px-3 mx-ns-4 bg-gradient-purple shadow blur d-flex w-100 justify-content-center align-items-center flex-column">
	<h3 class="text-center text-light font-weight-bolder">Friend Requests</h3>
</div>
<div class="row justify-content-center" style="margin-top:-2em;">
	<div class="col-lg-10 col-md-11 col-sm-12 col-xs-12">
        <div class="card rounded-0 shadow">
            <div class="card-body">
                <div class="container-fluid">
                    <div id="request-list" class="list-group">
                   <?php 
                   $requests = $conn->query("SELECT r.*, concat(m.firstname, ' ', coalesce(concat(m.middlename,' '),''),m.lastname) as `name`, m.email, m.avatar FROM `member_list` m inner join request_list r on r.member_id = m.id where  r.ask_member_id = '{$_settings->userdata('id')}' and r.`status` = 0 order by `name` asc");
                   while($row = $requests->fetch_assoc()):
                   ?>
                   <div class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 align-items-center">
                            <div class="col-auto">
                                <img src="<?= validate_image($row['avatar']) ?>" alt="" class="request-img rounded-circle border border-dark">
                            </div>
                            <div class="col-auto flex-shrink-1 flex-grow-1">
                                <div style="line-height:1.2em">
                                    <div class="font-weight-bolder"><?= $row['name'] ?></div>
                                    <div class="font-weight-bolder text-muted"><?= $row['email'] ?></div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <a href="./?page=user/profile&user_id=<?= $row['member_id'] ?>" class="btn btn btn-sm btn-flat btn-light bg-gradient-light border"> <i class="fa fa-user-circle"></i> View Profile</a>
                                <button class="btn btn-sm btn-flat btn-primary bg-gradient-primary" type="button" data-request-id="<?= $row['id'] ?>" id="confirm_request"><i class="fa fa-check"></i> Confirm</button>
                            </div>
                        </div>
                   </div>
                   <?php endwhile; ?>
                   </div>
                   <?php if($requests->num_rows <= 0): ?>
                    <h4 class="text-muted text-center"><i>There's no friend request yet.</i></h4>
                   <?php endif; ?>
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
    })
</script>