<style>
  .user-img {
    position: absolute;
    height: 27px;
    width: 27px;
    object-fit: cover;
    left: -7%;
    top: -12%;
}
.search-suggest-img{
  width:3em;
  height:3em;
  object-fit:cover;
  object-position:center center;
}
#search-suggest{
  max-height:20em;
  overflow:auto;
}
.request_count:empty{
  display:none !important
}
</style>
<nav class="main-header navbar navbar-expand-lg navbar-light bg-gradient-light border-bottom border-4 shadow">
            <div class="container px-4 px-lg-5 ">
                <button class="navbar-toggler btn btn-sm" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <a class="navbar-brand" href="./">
                <img src="<?php echo validate_image($_settings->info('logo')) ?>" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">
                <?php echo $_settings->info('short_name') ?>
                </a>
                <div id="search-form-folder" class=" w-25 position-relative">
                    <form action="" id="search-form">
                      <div class="input-group input-group-sm">
                        <input class="form-control form-control-sm rounded-0" name="search" id="search" type="search">
                        <button class="btn btn-outline-secondary btn-sm rounded-0"><i class="fa fa-search"></i></button>
                      </div>
                    </form>
                    <div class="list-group position-absolute w-100 rounded-0" id="search-suggest"></div>
                </div>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link" aria-current="page" href="./">Home</a></li>
                        <li class="nav-item"><a class="nav-link" id="upload-modal" aria-current="page" href="javascript:void(0)"><i class="far fa-plus-square mr-2"></i>Post</a></li>
                        <?php 
                        $request_count = $conn->query("SELECT id FROM `request_list` where ask_member_id = '{$_settings->userdata('id')}' and `status` = 0")->num_rows;
                        $request_count = $request_count > 0 ? format_num($request_count) : '';
                        ?>
                        <li class="nav-item"><a class="nav-link" aria-current="page" href="./?page=friends">Friends</a></li>
                        <li class="nav-item"><a class="nav-link" aria-current="page" href="./?page=friends/requests">Request <span class="ms-1 badge badge-warning rounded px-2 font-weight-bolder text-light request_count"><?= $request_count ?></span></a></li>
                        <li class="nav-item"><a class="nav-link" aria-current="page" href="./?page=user/profile">Profile</a></li>
                    </ul>
                    <div class="d-flex align-items-center">
                      <div class="btn-group nav-link text-reset">
                        <button type="button" class="btn btn-rounded badge badge-light dropdown-toggle dropdown-icon  text-reset" data-toggle="dropdown">
                          <span><img src="<?php echo validate_image($_settings->userdata('avatar')) ?>" class="img-circle elevation-2 user-img" alt="User Image"></span>
                          <span class="ml-3"><?php echo ucwords($_settings->userdata('firstname').' '.$_settings->userdata('lastname')) ?></span>
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                          <a class="dropdown-item" href="<?php echo base_url.'user/?page=user' ?>"><span class="fa fa-user"></span> My Account</a>
                          <div class="dropdown-divider"></div>
                          <a class="dropdown-item" href="<?php echo base_url.'/classes/Login.php?f=user_logout' ?>"><span class="fas fa-sign-out-alt"></span> Logout</a>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </nav>
<noscript id="search-suggest-item-clone">
<a href="" class="list-group-item list-group-item-action text-reset">
  <div class="d-flex w-100 align-items-center">
    <div class="col-auto text-center">
      <img src="<?= validate_image('') ?>" alt="" class="rounded-circle border border-dark search-suggest-img">
    </div>
    <div class="col-auto flex-shrink-1 flex-grow-1">
      <div style="line-height:1.1em">
        <div class="fw-bolder username">Test</div>
        <div class="fw-light email">test@sample.com</div>
      </div>
    </div>
  </div>
</a>
</noscript>
<script>
  var process_ajax = false;
  $(function(){
    $('#search').on('input change', function(){
      var kw = $(this).val()
      if(kw == ''){
        $('#search-suggest').html('')
      }else{
        if(!!process_ajax)
        process_ajax.abort()
        process_ajax = $.ajax({
          url:_base_url_+"classes/Master.php?f=search_user",
          method:'POST',
          data:{search:kw},
          dataType:'json',
          error:err=>{
            console.log(err)
            alert("Fetching Search Suggestion Failed due to unknown reason.")
            process_ajax.abort()
            process_ajax=false
          },
          success:function(resp){
            $('#search-suggest').html('')
            if(resp.status == 'success'){
              if(Object.keys(resp.data).length > 0){
                Object.keys(resp.data).map(k=>{
                  var user = resp.data[k]
                  var anchor = $($('noscript#search-suggest-item-clone').html()).clone()
                  anchor.find('.search-suggest-img').attr('src', user.avatar)
                  anchor.find('.username').text(user.name)
                  anchor.find('.email').text(user.email)
                  anchor.attr('href', _base_url_+"user/?page=user/profile&user_id="+user.id)
                  $('#search-suggest').append(anchor)
                })
              }
            }else{
              alert("Fetching Search Suggestion Failed due to unknown reason.")
              process_ajax.abort()
              process_ajax=false
            }
          }
        })
      }
    })
    $('#login-btn').click(function(){
      uni_modal("","login.php")
    })
    $('#navbarResponsive').on('show.bs.collapse', function () {
        $('#mainNav').addClass('navbar-shrink')
    })
    $('#navbarResponsive').on('hidden.bs.collapse', function () {
        if($('body').offset.top == 0)
          $('#mainNav').removeClass('navbar-shrink')
    })
    $('#upload-modal').click(function(){
      uni_modal('<i class="far fa-plus-square"></i> Add New Post','posts/manage_post.php','modal-lg')
    })
  })

</script>