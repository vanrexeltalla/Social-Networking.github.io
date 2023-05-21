
<style>
    .friend-img{
    width:8em;
    height:8em;
    object-fit:cover;
    object-position:center center;
    }
    .friend-item{
        transition: all .09s ease-in-out;
    }
    .friend-item:hover{
        transform:scale(1.02)
    }
</style>
<div class="mx-0 py-5 px-3 mx-ns-4 bg-gradient-purple shadow blur d-flex w-100 justify-content-center align-items-center flex-column">
	<h3 class="text-center text-light font-weight-bolder">Friend List</h3>
</div>
<div class="row justify-content-center" style="margin-top:-2em;">
	<div class="col-lg-10 col-md-11 col-sm-12 col-xs-12">
        <div class="card rounded-0 shadow">
            <div class="card-body">
                <div class="container-fluid">
                    <div class="row mx-0 justify-content-center mb-4">
                        <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm rounded-0 border-right-0" id="search-friend" placeholder="Search Friend">
                                <span class="input-group-text border-right border-top border-bottom rounded-0"><i class="fa fa-search"></i></span>
                            </div>
                        </div>
                    </div>
                    <div id="friend-list" class="row">
                    <?php 
                    $requests = $conn->query("SELECT *, concat(firstname, ' ', coalesce(concat(middlename,' '),''),lastname) as `name` FROM `member_list` where  id in (SELECT member_id from request_list where ask_member_id = '{$_settings->userdata('id')}' and `status` = 1) or id in (SELECT ask_member_id from request_list where member_id = '{$_settings->userdata('id')}' and `status` = 1) order by `name` asc");
                    while($row = $requests->fetch_assoc()):
                    ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 py-1 px-2 position-relative overflow-hidden">
                        <a href="./?page=user/profile&user_id=<?= $row['id'] ?>" class="card rounded-0 shadow text-reset text-decoration-none friend-item">
                            <div class="img-top text-center">
                                <img src="<?= validate_image($row['avatar']) ?>" alt="" class="friend-img rounded-circle border border-dark">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center w-100"><?= $row['name'] ?></h5>
                                <div class="text-center card-description w-100 text-muted"><?= $row['email'] ?></div>
                            </div>
                        </a>
                    </div>
                    <?php endwhile; ?>
                   </div>
                   <?php if($requests->num_rows <= 0): ?>
                    <h4 class="text-muted text-center"><i>There's no friend request yet.</i></h4>
                   <?php endif; ?>
                   <h4 id="noRS" class="text-muted text-center d-none"><i>No search result.</i></h4>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#search-friend').on('input change', function(){
            var kw = $(this).val().toLowerCase()
            $("#friend-list .friend-item").each(function(){
                var txt = $(this).text().toLowerCase();
                if((txt).includes(kw) == true){
                    $(this).parent().toggle(true)
                }else{
                    $(this).parent().toggle(false)
                }
            })
            if($("#friend-list .friend-item:visible").length <= 0){
                if($('#noRS').hasClass('d-none') == true){
                    $('#noRS').removeClass('d-none') 
                }
            }else{
                if($('#noRS').hasClass('d-none') == false){
                    $('#noRS').addClass('d-none') 
                }
            }
        })
        
    })
</script>