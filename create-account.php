<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main2.css">  
    <link rel="stylesheet" href="css/signup.css">
        
    <title>Create Account</title>
    <style>
        .container{
            animation: transitionIn-X 0.5s;
        }
    </style>
</head>
<body>
<?php
session_start();

// Unset any existing session values
$_SESSION["user"] = "";
$_SESSION["usertype"] = "";

// Set timezone
date_default_timezone_set('Asia/Kolkata');
$_SESSION["date"] = date('Y-m-d');

// Include DB connection
include("connection.php");

$error = ""; // Make sure $error is defined to avoid undefined variable warnings

if ($_POST) {
    $fname = $_SESSION['personal']['fname'];
    $lname = $_SESSION['personal']['lname'];
    $name = $fname . " " . $lname;
    $address = $_SESSION['personal']['address'];
    $nic = $_SESSION['personal']['nic'];
    $dob = $_SESSION['personal']['dob'];
    $email = $_POST['newemail'];
    $tele = $_POST['tele'];
    $newpassword = $_POST['newpassword'];
    $cpassword = $_POST['cpassword'];

    if ($newpassword == $cpassword) {
        $sqlmain2 = "SELECT * FROM webuser WHERE email=?;";
        $stmt = $database->prepare($sqlmain2);

        if (!$stmt) {
            die("Prepare failed: (" . $database->errno . ") " . $database->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>';
        } else {
            // Insert patient
            $insert_patient = $database->prepare("INSERT INTO patient(pemail, pname, ppassword, paddress, pnic, pdob, ptel) VALUES (?, ?, ?, ?, ?, ?, ?)");

            if (!$insert_patient) {
                die("Prepare failed (patient): (" . $database->errno . ") " . $database->error);
            }

            $insert_patient->bind_param("sssssss", $email, $name, $newpassword, $address, $nic, $dob, $tele);
            $insert_patient->execute();

            // Insert webuser
            $insert_webuser = $database->prepare("INSERT INTO webuser(email, usertype) VALUES (?, 'p')");

            if (!$insert_webuser) {
                die("Prepare failed (webuser): (" . $database->errno . ") " . $database->error);
            }

            $insert_webuser->bind_param("s", $email);
            $insert_webuser->execute();

            // Set session values
            $_SESSION["user"] = $email;
            $_SESSION["usertype"] = "p";
            $_SESSION["username"] = $fname;

            header('Location: patient/index.php');
            exit;
        }
    } else {
        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Confirmation Error! Please confirm your password correctly.</label>';
    }
} else {
    $error = '<label for="promter" class="form-label"></label>';
}
?>

<!-- HTML Part -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main2.css">  
    <link rel="stylesheet" href="css/signup.css">
    <style>
        .container {
            animation: transitionIn-X 0.5s;
        }
    </style>
</head>
<body>
<center>
<div class="container">
    <form action="" method="POST">
        <table border="0" style="width: 69%;">
            <tr>
                <td colspan="2">
                    <p class="header-text">Let's Get Started</p>
                    <p class="sub-text">It's Okey, Now Create User Account.</p>
                </td>
            </tr>

            <tr>
                <td class="label-td" colspan="2">
                    <label for="newemail" class="form-label">Email: </label>
                    <input type="email" name="newemail" class="input-text" placeholder="Email Address" required>
                </td>
            </tr>

            <tr>
                <td class="label-td" colspan="2">
                    <label for="tele" class="form-label">Mobile Number: </label>
                    <input type="tel" name="tele" class="input-text" placeholder="ex: 0712345678" pattern="[0]{1}[0-9]{9}">
                </td>
            </tr>

            <tr>
                <td class="label-td" colspan="2">
                    <label for="newpassword" class="form-label">Create New Password: </label>
                    <input type="password" name="newpassword" class="input-text" placeholder="New Password" required>
                </td>
            </tr>

            <tr>
                <td class="label-td" colspan="2">
                    <label for="cpassword" class="form-label">Confirm Password: </label>
                    <input type="password" name="cpassword" class="input-text" placeholder="Confirm Password" required>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <?php echo $error ?>
                </td>
            </tr>

            <tr>
                <td><input type="reset" value="Reset" class="login-btn btn-primary-soft btn"></td>
                <td><input type="submit" value="Sign Up" class="login-btn btn-primary btn"></td>
            </tr>

            <tr>
                <td colspan="2">
                    <br>
                    <label for="" class="sub-text" style="font-weight: 280;">Already have an account? </label>
                    <a href="login.php" class="hover-link1 non-style-link">Login</a>
                    <br><br><br>
                </td>
            </tr>
        </table>
    </form>
</div>
</center>
</body>
</html>
