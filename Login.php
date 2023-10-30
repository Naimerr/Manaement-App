<?php
class Login{
    function __construct() {
        session_start();
        if (isset($_SESSION['user'])) {
            header('Location: Home.php');
            exit();
        }
    }

    function login($username, $password, $email = NULL) {
        $validation = $this->validation(['username' => $username,'password'=> $password]);
        if ($validation === true){
            $recoveredData = file_get_contents('./data/userList.json');
            $userList = json_decode($recoveredData);
            
            if (!empty($userList)) {
                $i = 0;
                foreach ($userList as $key => $user) {
                    if (trim($user->username) == trim($username)) {
                        if (trim($user->password) == sha1(trim($password))) {
                            return $this->setSession($user);
                        }
                    }
                    $i++;
                }
                $_SESSION['warning'] = 'Your username or password is invalid';
            }else{
                $_SESSION['warning'] = 'Your username is not registered';
            }
            
        }else{
            $_SESSION['warning'] = $validation;
        }
        
    }

    function validation($data) {
        if (trim($data['username']) == '') {
            return "The username field is required";
        }elseif (trim($data["password"]) == ""){
            return "The password field is required";
        }
        return true;
    }

    function setSession($user) {
	    session_start();
        unset($user->password);
        $_SESSION['user'] = $user;
        header('location: Home.php');
    }

    function logOut() {
        session_destroy();
        header('location: LoginForm.php');
        exit();
    }
}
$logObj = new Login();
if (isset($_POST['login'])) {
    $response = $logObj->login($_POST["username"], $_POST["password"]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body class="">
    <div class="container mt-5">
        <div class="row ">
            <div class="mask d-flex align-items-center h-100 gradient-custom-3  ">
                <div class="container h-100">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                            <div class="card" style="border-radius: 15px;">
                                <div class="card-body p-5">

                                <form method="post" action="">
                                    <?php 
                                    if (isset($_SESSION['message'])){
                                        ?>
                                        <div class="alert alert-success" role="alert">
                                        <?= $_SESSION['message']; ?>

                                        </div>
                                        <?php
                                        unset($_SESSION['message']);
                                    }

                                    if (isset($_SESSION['warning'])){
                                        ?>
                                        <div class="alert alert-warning" role="alert">
                                        <?= $_SESSION['warning']; ?>

                                        </div>
                                        <?php
                                        unset($_SESSION['warning']);
                                    }

                                    ?>
                                    <h3>Login</h3>
                                    <hr>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" name="username" value="<?= isset($_POST['username'])? trim($_POST['username']):"" ?> " class="form-control" id="username" placeholder="Username">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" name="login" class="btn btn-primary">Login</button>
                                    </div>
                                    <div class="mb-3">
                                        Create a new account
                                        <a href="Registration.php" class="btn btn-secondary" >Registration</a>
                                        
                                    </div>
                                </form>

                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
        </div>
    </div>
</body>

</html>