<style>
.Ginfo{
    font-size: 1.5rem;
}
.info {
    font-weight: bold;
    font-size: 1.5rem;
  margin: 3px 50px;

    width: auto;
    color: #495867;
}
.likes{
      margin: 3px 50px;
      font-size: 1.5rem;
      color: #f07167;
}



</style>

<?php

// Create connection to Oracle

$conn = oci_pconnect("ora_zinniali", "a88978812", "dbhost.students.cs.ubc.ca:1522/stu");
if (!$conn) {
   $m = oci_error();
   echo $m['message'], "\n";
   exit;
}else {
   print "HEYFOOD!";
}

session_start();
$uri = $_SERVER['PHP_SELF'] . "?" . SID;
if( isset($_POST['dbuser']) ) {
	$dbuser = $_POST['dbuser'];
}
if( isset($_POST['pass']) ) {
	$pass = $_POST['pass'];
}

//echo "This is ". $_POST['rid'];
$thismanager=$_POST['rid'];
// prepare the query for presenting table with food name, price, number of customer likes the food
$stid = oci_parse($conn, "SELECT f.fid,f.name, f.price
     FROM Food f,Manages m
     WHERE m.rid=f.rid AND m.mid=$thismanager");//// DEBUG:


    oci_define_by_name($stid, 'FID', $fid);
    oci_define_by_name($stid, 'NAME', $name);
    oci_define_by_name($stid, 'PRICE', $price);
    oci_execute($stid);


  echo "<div class=\"info\"><br>\n Hi, Manager :)
  <br>\n Restuarant menu and number of likes got from customers:<br></div>\n";
    // while (oci_fetch($stid)&&oci_fetch($stid_count)) {
while (oci_fetch($stid)){
  $stid_count = oci_parse($conn, "SELECT count(*) AS num FROM CustomerLikes GROUP BY fid HAVING fid=$fid");
  oci_define_by_name($stid_count, 'NUM', $numlike);
  oci_execute($stid_count);
  while (oci_fetch($stid_count)){
        echo "<div class=\"info\"><br>\n<br>\nName: $name <br></div>\n";
        echo "<div class=\"info\">Phone number: $price <br></div>\n";
        echo "<div class=\"likes\">Liked by $numlike customers</div>";
      }}
  ?>

  <!DOCTYPE HTML>
  <html>
      <head>
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <title></title>
      </head>
      <body>


      </body>
  </html>
