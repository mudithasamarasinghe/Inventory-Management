<?php require_once('../config.php') ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
<?php require_once('inc/header.php') ?>
<body class="hold-transition login-page dark-mode">
<script>
    start_loader()
</script>
<style>
    body{
        background-image: url("https://www.regdesk.co/wp-content/uploads/2020/01/Changing-face-medical-device-design-1536x1536-300.jpg");
        background-size: cover;
        background-repeat: no-repeat;
    }
    .login-title{
        text-shadow: 2px 2px black
    }
</style>
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="./" class="h1"><b>WELCOME</b></a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign in to Continue</p>

            <form id="login-frm" action="" method="post">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" autofocus name="username" placeholder="Username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" name="password" id="id_password" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="far fa-eye" id="togglePassword"></i>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block" style="align-items: center">Sign In</button>
                    </div>
                </div>
            </form>
            <!-- /.social-auth-links -->

            <!-- <p class="mb-1">
              <a href="forgot-password.html">I forgot my password</a>
            </p> -->

        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
    $(document).ready(function(){
        end_loader();
    })

    // Toggle Password Visibility
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#id_password');

    togglePassword.addEventListener('click', function (e) {
        // Toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // Toggle the eye icon
        this.classList.toggle('fa-eye-slash');
    });
</script>
</body>
</html>
