<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title>CI</title>
	<?php css([
		'style/bower/bootstrap/dist/css/bootstrap.min',
		'style/bower/font-awesome/css/font-awesome.min',
    'style/bower/datatables.net-bs/css/dataTables.bootstrap.min',
		'style/css/AdminLTE.min',
		'style/css/skins/_all-skins.min',
	]) ?>
	<?php js([
    'style/bower/jquery/dist/jquery.min',
    'style/bower/jquery-ui/jquery-ui.min',
    'style/bower/bootstrap/dist/js/bootstrap.min',
    'style/bower/datatables.net/js/jquery.dataTables.min',
    'style/bower/datatables.net-bs/js/dataTables.bootstrap.min',
  ]) ?>
</head>
<body class="hold-transition skin-blue sidebar-mini fixed">
<div class="wrapper">
  <header class="main-header">
    <!-- Logo -->
    <a href="" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>LTE</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">Admin <b>LTE</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?=$img_user=base_url('assets/images/'.(empty(getSession('foto'))?'duser.png':getSession('foto')))?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?=getSession('nama')?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img src="<?=$img_user?>" class="img-circle" alt="User Image">
                <p>
                  <?=getSession('nama')?>
                  <small>login at : <?=date('d, F Y H:i:s',strtotime(getSession('login_at')))?></small>
                </p>
              </li>

              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?=site_url('profil')?>" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?=site_url('logout')?>" onclick="return confirm('Yakin akan LogOut ?')" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?=$img_user?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?=ellipsize(getSession('nama'),18,0.5)?></p>
          <a href="javascript:void(0)"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <?=create_menus([['icon'=>'fa fa-dashboard','name'=>'dashboard','display'=>'Dashboard','link'=>'dashboard']],$active_menu)?>
        <?php if(can_access('admin')){ echo create_menus(list_menu(),$active_menu);}?>
        <?=create_menus([['icon'=>'fa fa-briefcase','name'=>'pelayanan','display'=>'Pelayanan','link'=>'pelayanan']],$active_menu)?>
      </ul>
    </section>
  </aside>

  <div class="content-wrapper">
  	<?php if(!empty(getFlash('error'))||!empty(getFlash('success'))){ ?>
    	<section class="content-header">
    		<?php if(!empty(getFlash('error'))){ ?>
    			<div class="alert anotif alert-danger alert-dismissible">
    				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    				<h4><i class="icon fa fa-ban"></i> ERROR!</h4>
    				<?=getFlash('error')?>
    			</div>
        	<?php }
        	 if(!empty(getFlash('success'))){ ?>
    			<div class="alert anotif alert-success alert-dismissible">
    				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    				<h4><i class="icon fa fa-check"></i> SUCCESS!</h4>
    				<?=getFlash('success')?>
    			</div>
        	<?php } ?>
    	</section>
  	<?php } ?>
	<section class="content"><?=$konten; ?></section>
  </div>

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 0.0.1
    </div>
    <strong>Copyright &copy; 2019 <a href="#">Admin LTE</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs"></ul>
    <div class="tab-content"><div class="tab-pane" id="control-sidebar-home-tab"></div></div>
  </aside>
  <!-- end right side -->
  <div class="control-sidebar-bg"></div>
</div>
	<?php js([
    'style/bower/jquery-slimscroll/jquery.slimscroll.min',
		'style/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min',
    'style/js/adminlte.min',
    'style/js/demo',
		'style/js/custom',
	]) ?>
	<script type="text/javascript">
    $.extend( true, $.fn.dataTable.defaults, {
        dom : "<'row m-t-15'<'col-sm-6'l><'col-sm-6'f>>"+
              "<'row'<'col-sm-12 m-t--15'<'table-responsive't>r>>"+
              "<'row'<'col-sm-5 m-t--15'i><'col-sm-7 m-t--15'p>>",
    });
    setTimeout(function() { $('.anotif').hide('fade'); }, 15000);
    $(document).ready(()=>{
      $('.treeview .menu-open').parent().show();$('.treeview .menu-open').parents('.treeview').addClass('menu-open')
    });
	</script>
</body>
</html>
