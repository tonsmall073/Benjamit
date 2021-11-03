<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>SB Admin 2 - Login</title>

        <!-- Custom fonts for this template-->
        <link href="vendor/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="vendor/fontawesome-free/css/all.css" rel="stylesheet" type="text/css">
        <link href="css/Font-Nunito.css" rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="css/sb-admin-2.css" rel="stylesheet">

        <!-- SweetAlert2 for all pages-->
        <link href="css/SweetAlert2/V11.1.9/sweetalert2.css" rel="stylesheet">

    </head>
    <style>
    .form-login-height {
        height: 600px;
    }

    .input-login-empty {
        border-color: red;
    }

    </style>

    <body class="bg-gradient-primary-login">

        <div class="container">

            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-xl-10 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row form-login-height">
                                <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                                <div class="col-lg-6">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">เข้าสู่ระบบ ร้านเบ็ญจมิตร</h1>
                                        </div>
                                        <form class="user" id="userLogin">
                                            <div class="form-group">
                                                <input type="text" class="form-control form-control-user"
                                                    id="inputUsername" placeholder="ID ผู้ใช้งาน (UserID)"
                                                    title="กรุณาป้อนข้อมูล ID ใช้งาน (UserID) ครับ">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control form-control-user"
                                                    id="inputPassword" placeholder="รหัสผ่าน (Password)"
                                                    title="กรุณาป้อนข้อมูล รหัสผ่าน (Password) ครับ">
                                            </div>
                                            <!-- <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember
                                                    Me</label>
                                            </div>
                                        </div> -->
                                            <a href="#" class="btn btn-primary btn-user btn-block" id='buttonLogin'
                                                onclick="userLogin();">
                                                Login
                                            </a>
                                            <!-- <hr>
                                        <a href="index.html" class="btn btn-google btn-user btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login with Google
                                        </a>
                                        <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                        </a> -->
                                        </form>
                                        <!-- <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.html">Create an Account!</a>
                                    </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.js"></script>
        <script src="js/main.js"></script>

        <!-- SweetAlert2 for all pages-->
        <script src="js/SweetAlert2/V11.1.9/sweetalert2.js"></script>

        <script>
        async function checkInputLogin() {
            try {
                let chkSwal2Alerted = 0;

                const forms = document.querySelectorAll('#userLogin input');

                for (const elem of forms) {
                    if ($(`#${elem.id}`).val() == '') {
                        $(`#${elem.id}`).addClass('input-login-empty alert-danger');

                        if (chkSwal2Alerted == 0) {
                            await $(`#${elem.id}`).focus();

                            await Swal.fire({
                                "icon": 'warning',
                                "text": elem.title,
                                "showConfirmButton": true,
                                "confirmButtonText": 'OK',
                                "confirmButtonColor": '#d33',
                                "timer": 5000
                            });
                            chkSwal2Alerted = 1;
                        }
                    } else {
                        $(`#${elem.id}`).removeClass('input-login-empty alert-danger');
                    }
                }

                if (chkSwal2Alerted == 1) return false;

                return true;
            } catch (err) {
                alert(`Function checkInputLogin Error : ${err.message}`);
                return false;
            }

        }

        async function userLogin() {
            const chkInput = await checkInputLogin();

            if (chkInput != true) return false;

            let createDatas = new FormData();
            createDatas.append('Controller', 'CheckLogin');
            createDatas.append('Username', $('#inputUsername').val());
            createDatas.append('Password', $('#inputPassword').val());

            let req = {};
            await createDatas.forEach(async (value, key) => {
                req[key] = value;
            });

            const res = await asyncSendPostApi('Services/Login/Login.controller.php', req);

            const setSwal2 = res.Status == 200 ? {
                commandIcon: 'success',
                txtContent: `${res.Content.FullName} (${res.Content.NickName}) ล็อกอินเข้าสู่ระบบเรียบร้อย`,
                btColor: '#34a853'
            } : {
                commandIcon: 'error',
                txtContent: 'ล็อกอิน ไม่สำเร็จ UserId หรือ Password ไม่ตรงกับระบบ',
                btColor: '#d33'
            };

            await Swal.fire({
                "icon": setSwal2.commandIcon,
                "text": setSwal2.txtContent,
                "showConfirmButton": true,
                "confirmButtonText": 'OK',
                "confirmButtonColor": setSwal2.btColor,
                "timer": 5000
            });

            if (res.Status == 200) location.href = 'index.php';

            return true;
        }

        asyncAddPressActionClick('#buttonLogin', 13, '#userLogin');
        </script>

    </body>

</html>
