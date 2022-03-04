<?php
/** database connection credentials */

/** other variables */
$userNameIsUnique = true;
$passwordIsValid = true;
$userIsEmpty = false;
$passwordIsEmpty = false;
$password2IsEmpty = false;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_POST['user'] == "")
        $userIsEmpty = true;
    $con = oci_connect("ora_zinniali", "a88978812", "dbhost.students.cs.ubc.ca:1522/stu");
    if (!$con) {
        $m = oci_error();
        exit('Connect Error ' . $m['message']);
    }
    $query = "SELECT cid FROM Customer WHERE cid = :user_bv";
    $stid = oci_parse($con, $query);
    $user = $_POST['user'];

    oci_bind_by_name($stid, ':user_bv', $user);
    oci_execute($stid);

//Each user name should be unique. Check if the submitted user already exists.
    $row = oci_fetch_array($stid, OCI_ASSOC);
    if ($row) {
        $userNameIsUnique = false;
    }

    //Check for the existence and validity of the password
    if ($_POST['password'] == "")
        $passwordIsEmpty = true;
    if ($_POST['password2'] == "")
        $password2IsEmpty = true;
    if ($_POST['password'] != $_POST['password2']) {
        $passwordIsValid = false;
    }

    //If everything is OK, add the new user name and password to the database
    if (!$userIsEmpty && $userNameIsUnique && !$passwordIsEmpty && !$password2IsEmpty && $passwordIsValid) {

        $query = "INSERT INTO Customer (cid, name,phone,password) VALUES (:user_bv, :name_bv, :phone_bv, :pwd_bv)";
        $stid = oci_parse($con, $query);
        $pwd = $_POST['password'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        oci_bind_by_name($stid, ':user_bv', $user);
        oci_bind_by_name($stid, ':name_bv', $name);
        oci_bind_by_name($stid, ':phone_bv', $phone);
        oci_bind_by_name($stid, ':pwd_bv', $pwd);
        oci_execute($stid);
        echo "Created";
        oci_free_statement($stid);
        oci_close($con);
        header('Location: searchindex.php');
        exit;
    }
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Join HEYFOOD</title>
        <link rel="stylesheet" href="includes/style.css">

            <!-- Font Awesome Icon -->
              <script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>
    </head>
    <body>
        <i class="fas fa-utensils"></i><h1>HEYFOOD REVIEW</h1>
        :)Nice to meet you!<br>
        Hope you will like HEYFOOD REVIEW<br><br><br>
        <form action="createNewCustomer.php" method="POST">
            Account Number: <input type="text" name="user"/><br/>
            <?php
            if ($userIsEmpty) {
                echo ("Enter your account number, please!");
                echo ("<br/>");
            }
            if (!$userNameIsUnique) {
                echo ("The account number already exists. Please choose another one and try again");
                echo ("<br/>");
            }
            ?>
            User Name: <input type="text" name="name"/><br/>

            Phone Number: <input type="text" name="phone"/><br/>

            Password (all numbers): <input type="password" name="password"/><br/>
            <?php
            if ($passwordIsEmpty) {
                echo ("Enter the password, please!");
                echo ("<br/>");
            }
            ?>
            Please confirm your password: <input type="password" name="password2"/><br/>
            <?php
            if ($password2IsEmpty) {
                echo ("Confirm your password, please");
                echo ("<br/>");
            }
            if (!$password2IsEmpty && !$passwordIsValid) {
                echo ("The passwords do not match!");
                echo ("<br/>");
            }
            ?>
            <input type="submit" value="Register"/>
        </form>
    </body>
</html>
