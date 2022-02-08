<!doctype html>
<html lang="en">
<head>
    <title>AutoBot - Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/loginStyle.css">
</head>
<body>  
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <h2 class="heading-section">AutoBot - Login</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-5">
                    <div class="login-wrap p-4 p-md-5">
                        <div class="icon d-flex align-items-center justify-content-center">
                            <span class="fa fa-user-o"></span>
                        </div>
                        <h3 class="text-center mb-4">Sign In</h3>
                        <form action="#" class="login-form" method="POST">
                            <div class="form-group">
                                <input type="text" id="username" class="form-control rounded-left" placeholder="Username" required>
                            </div>
                            <div class="form-group d-flex">
                                <input type="password" id="password" class="form-control rounded-left" placeholder="Password" required>
                            </div>
                            <div class="form-group">
                                <button type="button" class="form-control btn btn-primary rounded submit px-3 mySub">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<script src="<?=base_url()?>assets/js/jquery.min.js"></script>
</body>
<script>
$(document).ready(function(){

    $('.mySub').click(function(){
        signIn();
    });
    
});

function signIn(){

    let params = {};

    params['username'] = $('#username').val();

    params['password'] = $('#password').val();

    $.post("<?=site_url('login/signIn')?>",params,(data) => {
        let obj = JSON.parse(data);

        if(obj.statuscode=='TXN'){

            window.location.href = "<?=site_url('home')?>";
        }
        else{

            alert(obj.status);
        }
    });
}
</script>
</html>
