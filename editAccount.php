<?php
$con = oci_pconnect("ora_zinniali", "a88978812", "dbhost.students.cs.ubc.ca:1522/stu");

$ratingIsEmpty = false;

if ($_SERVER['REQUEST_METHOD'] == "POST") {

   /** Checks whether the $_POST array contains an element with the "back" key */
   if (array_key_exists("back", $_POST)) {
       /** The Back to the List key was pressed.
        * Code redirects the user to the editAcc.php */
       header('Location: editAcc.php');
       exit;
   }
else{
$query = "UPDATE  Customer SET phone=:phone_bv, name=:name_bv WHERE cid = 3";

//// DEBUG:
// $CID=$_POST['willedit'];
// echo "Here is $CID";
//$CID=3;
$stid = oci_parse($con, $query);
$phone=$_POST['phone'];
$name=$_POST['name'];
oci_bind_by_name($stid, ':phone_bv', $phone);
oci_bind_by_name($stid, ':name_bv', $name);
//oci_bind_by_name($stid, ':id_bv', $CID);
oci_execute($stid);

header('Location: editAcc.php' );}}
?>

<!DOCTYPE HTML>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Edit Info</title>
        <link rel="stylesheet" href="includes/style.css">

    <!-- Font Awesome Icon -->
      <script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>

    </head>
    <body>
        <form name="edit" action="editAccount.php" method="POST">

            Change user name: <input type="text" name="name" /><br/>
            Change phone number: <input type="text" name="phone" /><br/>
            <input type="submit" name="save" value="Save Changes"/>
            <input type="submit" name="back" value="Back to the List"/>
        </form>
    </body>
</html>
