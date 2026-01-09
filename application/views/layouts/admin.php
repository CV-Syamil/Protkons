<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?=APP_NAME?></title>
  <link rel="icon" href="https://elektrikalpanel.com/images/temp/id.png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="<?=base_url('style/lte')?>/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css">
    
  <script src="<?=base_url('style/lte')?>/plugins/jquery/jquery.min.js"></script>
  <script src="<?=base_url('style/lte')?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
  <style type="text/css">
    .xtable .table-bordered{border: thin solid #eee !important;}
    .modal{overflow-y: auto !important;}
    .dtp-header{background: #d3196e!important;}
    .dtp-date{background: #e83e8c!important;}
    .dtp-date .material-icons, .dtp div.dtp-actual-year{color: white!important;}
    .dtp-select-day.selected{ background: #e83e8c!important; }
    .dtp-picker-year .btn-default{background: transparent; padding: 5px 0 0 0 !important;}
    .notif-msg{
      white-space: nowrap !important;
      text-overflow: ellipsis  !important;
      overflow: clip !important;
    }
  </style>
</head>
<body class="hold-transition layout-fixed layout-navbar-fixed sidebar-mini" id="main-body">
  <!-- Preloader -->
  <!-- <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="80" width="80">
  </div> -->
  
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-block d-lg-none">
          <a href="#" onclick="return false;" class="nav-link" style="padding-left: 0; margin-top:-5px; font-size: x-large;">
            <?=APP_NAME?>
          </a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge" id="notif_badge"></span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
            <span class="dropdown-item dropdown-header"><span id="notif_count">0</span> Notifikasi</span>
            <div class="dropdown-divider"></div>
            <div id="notif_list"> </div>
            <a href="<?=site_url('notifikasi')?>" class="dropdown-item dropdown-footer">Lihat Semua Notifikasi</a>
          </div>
        </li>
        <li class="nav-item">
          <div class="custom-control custom-switch custom-switch-on-success mt-2 ml-md-3">
            <input style="cursor: pointer;" type="checkbox" class="custom-control-input" id="sw_dark_mode">
            <label style="cursor: pointer; font-weight: normal !important; color: #eee;" class="custom-control-label" for="sw_dark_mode">Dark Mode &nbsp;&nbsp;</label>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-danger elevation-4">
      <!-- Brand Logo -->
      <a href="" class="brand-link">
          <img src="<?=base_url('assets/garuda.png')?>" alt="Garuda Logo" class="brand-image img-circle">
        <span class="brand-text font-weight-light"><?=APP_NAME?></span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="<?=getSession('foto')?>" style="width:2.1rem; height:2.1rem;" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info" style="margin-top:-10px">
            <a href="#" class="d-block">
              <?=getSession('nama')?>
              <div class="small"><?=getSession('nama_fungsi')?></div>
            </a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <?=create_menus([['icon'=>'fas fa-tachometer-alt','name'=>'dashboard','display'=>'Dashboard','link'=>'dashboard']],$active_menu)?>

          <?php 
            if(can_access(['admin','su'])){ 
              $msmenu=list_menu();
              if(can_access(['su'])){
                $msmenu[0]['submenu'][]=['icon'=>'fas fa-briefcase','name'=>'master_fungsi','display'=>'Fungsi','link'=>'master/fungsi'];
              } 
              echo create_menus($msmenu,$active_menu);
            } 
          ?>

          <?=create_menus([
              ['icon'=>'fas fa-briefcase','name'=>'pelayanan','display'=>'Pelayanan','link'=>'pelayanan'],
            ],$active_menu)?>
          
          <?php if(can_access(['verifikasi'])){ echo create_menus([
            ['icon'=>'fas fa-globe','name'=>'pelayanan_public','display'=>'Pelayanan (Online)','link'=>'pelayanan-publik']
          ],$active_menu);}?>
          
          <?php if(can_access(['loket','verifikasi'])){ echo create_menus([
            ['icon'=>'fas fa-users','name'=>'master_identitas_main_server','display'=>'Identitas (MAIN SERVER)','link'=>'server_master/identitas']
          ],$active_menu);}?>

          <?=(can_access(['admin'])?create_menus([
            ['icon'=>'fas fa-upload','name'=>'import_pelayanan','display'=>'Import Pelayanan','link'=>'import_pelayanan'],
            ['icon'=>'fas fa-briefcase','name'=>'inp_jml_pelayanan','display'=>'Input Jumlah Pelayanan','link'=>'jumlah_pelayanan'],
          ],$active_menu):'');?>
          <?=create_menus([['icon'=>'fas fa-file-invoice','name'=>'lap_pelayanan','display'=>'Laporan Pelayanan','link'=>'laporan/pelayanan']],$active_menu);?>
          <?php //(can_access(['admin','hs','kasir'])?create_menus([['icon'=>'fas fa-file-invoice','name'=>'lap_keuangan','display'=>'Laporan Keuangan','link'=>'laporan/keuangan']],$active_menu):'');?>
          <?=(can_access(['admin','hs','kasir','verifikasi','loket'])?create_menus([['icon'=>'fas fa-file-excel','name'=>'lap_per_pelayanan','display'=>'Laporan Per-Pelayanan','link'=>'laporan/per-pelayanan']],$active_menu):'');?>
          <?=create_menus([['icon'=>'fas fa-file-alt','name'=>'lap_pelapor','display'=>'Laporan Pelapor','link'=>'laporan/pelapor']],$active_menu);?>

            <li class="nav-item">
              <a href="<?=site_url('chat');?>" class="nav-link <?=($active_menu=='chat_menu'?'active':'')?>">
                <i class="nav-icon fas fa-comments"></i>
                <p>Chat</p>
              </a>
            </li>

            <li><hr style="border-top: 1px solid #4f5962;"></li>
            <li class="nav-item">
              <a href="<?=site_url('profil');?>" class="nav-link <?=($active_menu=='user_profil'?'active':'')?>">
                <i class="nav-icon fas fa-user-edit"></i>
                <p>User Profil</p>
              </a>
            </li>

            <li class="nav-item">
              <a href="#" onclick="logout();return false;" class="nav-link">
                <i class="nav-icon fas fa-power-off"></i>
                <p>Logout</p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content" style="padding: 20px;"><?=$konten;?></section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
      <!-- <div class="float-right d-none d-sm-block">
        <b>Version</b> <?=APP_VERSION?>
      </div> -->
      <strong>Copyright&copy;<?=date('Y')?> <a href="#" onclick="return false;">KBRI Kuala Lumpur</a>.</strong> All rights reserved.
    </footer>

  </div>
  <!-- ./wrapper -->

  <script src="<?=base_url('style/lte')?>/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <script src="<?=base_url('style/lte')?>/dist/js/adminlte.min.js"></script>
  <script src="<?=base_url('style/my.js')?>"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js" integrity="sha512-eYSzo+20ajZMRsjxB6L7eyqo5kuXuS2+wEbbOkpaur+sA2shQameiJiWEzCIDwJqaB0a4a6tCuEvCOBHUg3Skg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
  <style>
    .iziToast-color-yellow{
      background: var(--success) !important;
    }
  </style>
  <script>
    function showNotifikasi(title,msg){
      iziToast.show({
        theme: 'dark',
        color: 'yellow',
        title: title,
        displayMode: 2,
        message: msg,
        position: 'topCenter',
        transitionIn: 'flipInX',
        transitionOut: 'flipOutX',
        progressBarColor: '#15f',
        image: 'https://cdn-icons-png.flaticon.com/256/6828/6828737.png',
        imageWidth: 70,
        layout: 2,
        iconColor: '#15f'
      });
    }
  </script>
  <script>
    var first_notifikasi = true;
    var lastRef = 0;
    var notif_list=[];
    function getNotifikasi(){
      $.ajax({
        url: '<?=site_url('notifikasi/getnotifikasi')?>',type:'POST', data:{last:lastRef}, dataType:'JSON',noerror: true,
        complete:()=>setTimeout(() => { getNotifikasi(); }, 5000),
        success:(r)=>{
          lastRef = r.last;
          var timeout = 0;
          for (let index = r.msg.length; index > 0; index--) {
            let d = r.msg[index-1];
            notif_list.unshift(d);
            if(!first_notifikasi){
              setTimeout(() => {
                showNotifikasi(d.t,d.m);
              }, timeout);
            }
            timeout+=300;
          }
          if(r.msg.length>0){ generateNotif(); }
          let cn = r.cmsg??0;
          $('#notif_badge').html((cn>9)?'9+':((cn<=0)?'':cn));
          $('#notif_count').html(cn);
          first_notifikasi = false;
        }
      });
    }
    function generateNotif(){
      console.log('generate notifikasi');
      var html = '';
      notif_list.forEach((d,i)=>{
        if(i<5){
          html+=`<a href="${d.l}" class="dropdown-item">
                <span class="float-right text-muted mt-1" style="font-size:x-small">${d.w}</span>
                ${d.t}
                <div class="text-muted small notif-msg">${d.m}</div>
              </a>
              <div class="dropdown-divider"></div>`;
        }
      });
      $('#notif_list').html(html);
    }
    function setOnline(){
      $.ajax({
        url: '<?=site_url('chat/cek_online')?>',type:'GET', noerror: true,
        complete:()=>setTimeout(() => { setOnline(); }, 20000)
      });
    }
    $(document).ready(()=>setTimeout(() => { setOnline(); getNotifikasi(); }, 1000));
  </script>
  <script type="text/javascript">
    $.extend( true, $.fn.dataTable.defaults, {
        dom : "<'row m-t-15'<'col-md-4'l><'col-md-8'f>>"+
              "<'row'<'col-sm-12 m-t--15'<'table-responsive xtable't>r>>"+
              "<'row'<'col-md-5 m-t--15'i><'col-md-7 m-t--15'p>>",
    });
    toastr.options.closeButton = true;
    toastr.options.newestOnTop = false;
    toastr.options.showMethod = 'slideDown';
    toastr.options.hideMethod = 'slideUp';
    toastr.options.closeMethod = 'slideUp';
    toastr.options.progressBar = true;
    
    var swal_toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 2000
    });
    function toast(tipe,txt1,txt2="",timer=7000){
      switch (tipe) {
        case "error": toastr.error(txt1,txt2,{timeOut: timer});  break;
        case "success": toastr.success(txt1,txt2,{timeOut: timer});  break;
        case "warning": toastr.warning(txt1,txt2,{timeOut: timer});  break;
        case "info": toastr.info(txt1,txt2,{timeOut: timer});  break;
        default: case "info": toastr.info(txt1,txt2,{timeOut: timer}); break;
      }
      // swal_toast.fire({icon:tipe,title:txt1,text:txt2,timer:timer});
    }
    function logout(){
      Swal.fire({
        title: 'Logout User ?',
        text: "Logout user dari aplikasi.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Logout',
        showLoaderOnConfirm: true,
        preConfirm: ()=>{
          return new Promise(function(resolve, reject) {
            window.location = '<?=site_url('logout')?>';
          });
        },allowOutsideClick:false
      }).then((result)=>{ });
    }
    function blockUI(target,block=true){
      if(block){
        $(target).block({message:'loading...'});
      }else{
        $(target).unblock();
      }
    }

    $('#sw_dark_mode').on('change',function(){
      localStorage.setItem('kbri_dark_mode',(this.checked?1:0));
      set_dark_mode();
    });

    function set_dark_mode(){
      if(localStorage.getItem('kbri_dark_mode')==1){
        $('#main-body').addClass('dark-mode');
        $('#sw_dark_mode').prop('checked',true);
      }else{
        $('#main-body').removeClass('dark-mode');
        $('#sw_dark_mode').prop('checked',false);
      }
    }
    set_dark_mode();
    $(document).ready(()=>{
      $('.nav-treeview .menu-open').parent().show();$('.nav-treeview .menu-open').parents('.nav-item').addClass('menu-open')
    });
    $(document).ajaxError(function(e, r, s){
      if(r.statusText=="abort"||s.noerror){return;}
      if (r.status == 403) {
          toast('error',"Sorry, your session has expired. Please login again to continue");
      }else if (r.status == 419) {
          toast('error',"Sorry, your page has expired. Please refresh your page");
      }else if (r.status == 422&&r.responseJSON) {
          error_notif(r.responseJSON.errors);
      }else if(r.status==200){ toast('error','220','INVALID RESPONSE'); 
      }else { toast('error',r.status,r.statusText); }
    });
    function error_notif(msg){ if(msg){ if(typeof(msg)=='object'){ Object.values(msg).forEach((m,i)=>{toast('error',m);}); }else{toastr('error',msg);} } }
  </script>
    <?php 
      $e = getFlash('error');
      $s = getFlash('success');
      $time=100;
      $ec='';
      if(!empty(@$_SESSION['error'])){$_SESSION['error']=""; }
      if(!empty(@$_SESSION['success'])){$_SESSION['success']=""; }
      if(!empty($e)){
        preg_match_all('#<p>(.+?)</p>#',$e,$errs);
        if(empty($errs[1])){ $ec.="setTimeout(() => { toast('error','$e'); }, $time);"; $time+=100; }
        else{
          foreach($errs[1] as $err){ $ec.="setTimeout(() => { toast('error','$err'); }, $time);"; $time+=500; }
        }
      }
      if(!empty($s)){
        preg_match_all('#<p>(.+?)</p>#',$s,$scss);
        if(empty($scss[1])){ $ec.="setTimeout(() => { toast('success','$s'); }, $time);"; $time+=100; }
        else{
          foreach($scss[1] as $scs){ $ec.="setTimeout(() => { toast('success','$scs'); }, $time);"; $time+=500; }
        }
      }
      echo "<script>$ec</script>";
    ?>
</body>
</html>