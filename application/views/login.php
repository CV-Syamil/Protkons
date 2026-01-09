<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?=APP_NAME?> | LOGIN</title>
  <link rel="icon" href="https://elektrikalpanel.com/images/temp/id.png">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=base_url('style/lte')?>/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url('style/lte')?>/dist/css/adminlte.min.css">
  <style type="text/css">
    body{
        background: #45484d;
        background: -moz-linear-gradient(-45deg,  #45484d 0%, #000000 100%);
        background: -webkit-linear-gradient(-45deg,  #45484d 0%,#000000 100%);
        background: linear-gradient(135deg,  #45484d 0%,#000000 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#45484d', endColorstr='#000000',GradientType=1 );
    }
    .login-card{
      width: 100%;
      max-width: 380px;
      margin-top: 10vh;
    }
    .form-control:focus {
        border-color: #ffa29e;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-card">
  <!-- /.login-logo -->
  <div class="card card-outline card-danger">
    <div class="card-header text-center">
        <a href="<?=base_url()?>" class="h1"><img src="<?=base_url('assets/garuda.png')?>" alt="Garuda Logo" class="brand-image" style="max-width: 100px">
        <h3><?=APP_NAME?></h3></a>
    </div>
    <div class="card-body">
      <?php if(!empty($_GET['e'])){ echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$_GET['e'].'</div>'; }?>
        <!-- <div class="mb-2 mt-2 text-danger"><i class="icon fas fa-ban"></i> {{$message}}</div> -->

      <?=form_open('authenticate')?>
        <div class="input-group mb-3">
          <input type="name" name="usr" class="form-control" value="<?=@$_GET['usr']?>" placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="pwd" id="pwd" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock" style="cursor:pointer; color: #dc3444;" onclick="$(this).toggleClass('fa-lock fa-unlock','fa-unlock');$('#pwd').attr('type',(_,attr)=>{return (attr=='text'?'password':'text');});"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-danger btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-card -->

<!-- jQuery -->
<script src="<?=base_url('style/lte')?>/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?=base_url('style/lte')?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=base_url('style/lte')?>/dist/js/adminlte.min.js"></script>
</body>
</html>