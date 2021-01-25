<?php
include "dbconnect.php";

$userinput = $password = "";
$userinput_error = $password_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userinput = trim($_POST["userinput"]);
    $password = trim($_POST["password"]);

    if (empty($userinput_error) && empty($password_error)) {

        $sql = "SELECT id, username, fullname, password FROM user WHERE username = :userinput";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":userinput", $param_userinput, PDO::PARAM_STR);
            $param_userinput = trim($_POST["userinput"]);
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row["id"];
                        $userinput = $row["username"];
                        $userfullname = $row["fullname"];
                        $hashed_password = $row["password"];
                        if (password_verify($password, $hashed_password)) {
                            session_start();
                            $_SESSION["userloggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["userinput"] = $userinput;
                            $_SESSION["userfullname"] = $userfullname;
                            header("location: ../index.php");
                        } else {
                            $password_error = "The password you entered was invalid.";
                        }
                    }
                } else {
                    $userinput_error = "No account found with that username";
                }
            }
        }

        unset($pdo);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<body>

    <div class="d-flex justify-content-center mt-5">
        <form class="form-group" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2>Sign In</h2>
            <p>Please enter your username and password to sign in.</p>
            <div class="label_class"><label>Username</label></div>
            <div class="input_class"><input class="form-control" type="text" name="userinput" value="<?php echo $userinput; ?>" required></div>
            <span><?php echo $userinput_error; ?></span>

            <div><label>Password</label></div>
            <div><input class="form-control" type="password" name="password" value="<?php echo $password; ?>" required></div>
            <span><?php echo $password_error; ?></span>

            <div class="input_class mt-3">
                <input type="submit" class="btn btn-primary" value="Login">
                <input type="reset" class="btn btn-secondary" value="Reset">
                <a class="offset-2" href="register.php">Create a new account</a>
             </div>

        </form>
    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>