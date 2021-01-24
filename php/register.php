<?php
include "dbconnect.php";

if (isset($_SESSION["userloggedin"]) && $_SESSION["userloggedin"] === true) {
    header("Location: ../index.php");
    exit;
}

$user_name = $fullname = $password = $confirm_password = "";
$user_name_error = $fullname_error = $password_error = $confirm_password_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sql = "SELECT id FROM user WHERE username = :user_name";

    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":user_name", $param_user_name, PDO::PARAM_STR);
        $param_user_name = trim($_POST["user_name"]);

        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                $user_name_error = "This username is already taken.";
            } else {
                $user_name = trim($_POST["user_name"]);
            }
        } else {
            echo "Something went wrong. Please try again.";
        }
    }

    $sql = "SELECT id FROM user WHERE fullname = :fullname";

    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":fullname", $param_fullname, PDO::PARAM_STR);

        $param_fullname = trim($_POST["fullname"]);

        if ($stmt->execute()) {
            $fullname = trim($_POST["fullname"]);
        } else {
            echo "Something went wrong. Please try again.";
        }
    }

    if (strlen(trim($_POST["password"])) < 8) {
        $password_error = "Password must have at least 8 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    $confirm_password = trim($_POST["confirm_password"]);
    if (empty($password_error) && ($password != $confirm_password)) {
        $confirm_password_error = "Password did not match.";
    }

    if (empty($user_name_error) && empty($password_error) && empty($confirm_password_error) && empty($fullname_error)) {

        $sql = "INSERT INTO user (username, password, fullname) VALUES (:user_name, :password, :fullname)";

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":user_name", $param_user_name, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":fullname", $param_fullname, PDO::PARAM_STR);

            $param_user_name = $user_name;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_fullname = $fullname;

            if ($stmt->execute()) {
                $alert = "Your account has been created.";
                echo "<script type='text/javascript'>alert('$alert');</script>";
                header("Location: login.php");
            } else {
                $alert = "Error. Please try Again.";
                echo "<script type='text/javascript'>alert('$alert');</script>";
            }
        }
    }
    unset($pdo);
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body>

    <div class="d-flex justify-content-center mt-5">


        <form class="form-group" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <h2>Sign Up</h2>
            <p>Please fill this form to create an account.</p>
            <div <?php echo (!empty($user_name_error)) ? 'has-error' : ''; ?>">
                <div class="label_class"><label>Username</label></div>
                <div class="input_class"><input class="form-control" type="text" name="user_name" value="<?php echo $user_name; ?>" required></div>
                <span><?php echo $user_name_error; ?></span>
            </div>
            <div <?php echo (!empty($fullname_error)) ? 'has-error' : ''; ?>">
                <div class="label_class"><label>Full Name</label></div>
                <div class="input_class"><input class="form-control" type="fullname" name="fullname" value="<?php echo $fullname; ?>" required></div>
                <span><?php echo $fullname_error; ?></span>
            </div>
            <div  <?php echo (!empty($password_error)) ? 'has-error' : ''; ?>">
                <div class="label_class"><label>Password</label></div>
                <div class="input_class"><input class="form-control" type="password" name="password" value="<?php echo $password; ?>" required></div>
                <span><?php echo $password_error; ?></span>
            </div>
            <div  <?php echo (!empty($confirm_password_error)) ? 'has-error' : ''; ?>">
                <div class="label_class"><label>Confirm Password</label></div>
                <div class="input_class"><input class="form-control" type="password" name="confirm_password" value="<?php echo $confirm_password; ?>" required></div>
                <span><?php echo $confirm_password_error; ?></span>
            </div>
            <div>
                <div class="input_class mt-3">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <input type="reset" class="btn btn-secondary" value="Reset">
                </div>
            </div>
        </form>

    </div>

</body>

</html>