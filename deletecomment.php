<?php
//
// session_start();
// if (!array_key_exists("user", $_SESSION)) {
//     header('Location: res.php');
//     exit;
// }

$con = oci_pconnect("ora_zinniali", "a88978812", "dbhost.students.cs.ubc.ca:1522/stu");


$query = "DELETE FROM Review WHERE rvid = :id_bv";
$ID=$_POST['ID'];
$stid = oci_parse($con, $query);
oci_bind_by_name($stid, ':id_bv', $ID);
oci_execute($stid);
oci_close($conn);
header('Location: editAcc.php' );
?>
