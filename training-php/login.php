<?php
require_once 'models/UserModel.php';
$userModel = new UserModel();
?>
<!DOCTYPE html>
<html>
<head>
    <title>User form</title>
    <?php include 'views/meta.php' ?>
</head>
<body>
    <?php include 'views/header.php' ?>

    <div class="container">
        <div id="loginbox" style="margin-top:50px;" 
             class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">Login</div>
                </div>

                <div style="padding-top:30px" class="panel-body">
                    <form id="loginForm" class="form-horizontal" role="form">

                        <div class="margin-bottom-25 input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="login-username" type="text" class="form-control" name="username" placeholder="username or email">
                        </div>

                        <div class="margin-bottom-25 input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="login-password" type="password" class="form-control" name="password" placeholder="password">
                        </div>

                        <div class="margin-bottom-25">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember"> Remember Me</label>
                        </div>

                        <div class="margin-bottom-25 input-group">
                            <div class="col-sm-12 controls">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 control">
                                Don't have an account!
                                <a href="form_user.php">Sign Up Here</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const user = {
        username: formData.get("username"),
        password: formData.get("password")
    };

    // 1. Lưu vào LocalStorage (chỉ lưu username, không lưu password)
    localStorage.setItem("currentUser", JSON.stringify({ username: user.username }));

    // 2. Lưu vào Redis qua API
    fetch("http://localhost:8081/training-php/public/save_redis.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            key: "user:" + user.username,
            value: JSON.stringify(user)
        })
    })
    .then(res => res.json())
    .then(data => {
        console.log("Đã lưu Redis:", data);
        alert("Login thành công!");
        // Chuyển trang sau khi login
        window.location.href = "list_users.php";
    })
    .catch(err => {
        console.error("Lỗi Redis:", err);
        alert("Login thất bại!");
    });
});
</script>
</html>
