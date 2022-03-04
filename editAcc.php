
<?php
//
// session_start();
// if (array_key_exists("user", $_SESSION)) {
//     echo "Hello " . $_SESSION['user'];
// } else {
//     header('Location: res.php');
//     exit;
// }
?>

<!DOCTYPE HTML>
<html>
    <head>
      <title>Your Account Info</title>
        <link rel="stylesheet" href="includes/style.css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
      <h1>Your Personal Information</h1>
    <?php
    //customer basic information
      $CID=3;
      $con = oci_pconnect("ora_zinniali", "a88978812", "dbhost.students.cs.ubc.ca:1522/stu");
      $stid0 = oci_parse($con, "SELECT  cid,name,phone FROM  Customer WHERE cid =$CID");

      oci_define_by_name($stid0, 'CID', $cid);
      oci_define_by_name($stid0, 'NAME', $name);
      oci_define_by_name($stid0, 'PHONE', $phone);

     oci_execute($stid0);

          while (oci_fetch($stid0)) {
            echo "<div class=\"userpage\"> Account number: $cid <br>\n</div>";
            echo "<div class=\"userpage\"> Name: $name <br>\n</div>";
            echo "<div class=\"userpage\"> Phone number: $phone <br></div>\n";
          }

//for premium customer, show start and end date, for others show promotion info
$stid_pc = oci_parse($con, "SELECT pc.start_d,pcd.end_d FROM  PremiumCustomer pc, PreCusDate pcd WHERE cid=$CID
  AND TO_CHAR(pc.start_d,'DD-MON-YY')=TO_CHAR(pcd.start_d,'DD-MON-YY')");
oci_define_by_name($stid_pc, 'START_D', $start);
oci_define_by_name($stid_pc, 'END_D', $end);
oci_execute($stid_pc);

if (!oci_fetch($stid_pc))
echo("<div class=\"userpage\">
  <br>You are not a premium member yet!<br>
Send text message 'Premium' to XXX-XXX-XXXX to become a premium customer!<br></div>" );
else{
  echo "<div class=\"userpage\"><br><br>Your Membership starts at $start, and ends at $end<br></div>";
}



//coupon information
$stid_cp = oci_parse($con, "SELECT  cpid,value_worth,expiryDate FROM  GiftedCoupon WHERE cid =$CID");
             //$CID= $_SESSION['user'];
oci_define_by_name($stid_cp, 'CPID', $cpid);
oci_define_by_name($stid_cp, 'VALUE_WORTH', $value);
oci_define_by_name($stid_cp, 'EXPIRYDATE', $expirydate);

oci_execute($stid_cp);

if(!oci_fetch($stid_cp))
  echo "<div class=\"userpage\"><br>\nYou do not have any coupons yet :( <br> <br>\n";

while (oci_fetch($stid_cp)) {
echo " <br>\n";
echo "<div class=\"userpage\"> Coupon ID: $cpid <br>\n</div>";
echo "<div class=\"userpage\"> VALUE: $value <br>\n</div>";
echo "<div class=\"userpage\"> EXPIRES AT: $expirydate <br>\n</div>";  }
      ?>

  <form action="editAccount.php" method="GET" name="wishList">
      <?php // DEBUG:  ?>
      Change your personal information <input type="hidden" name="willedit" value="<?php echo $CID; ?>"/  >
      <input type="submit" value="Go" />
  </form>


        <table border="black">
          <br>Here are all the comments you have made:
          <tr><th>Rate</th><th>More Comments</th></tr>
      <?php

      $query = "SELECT rvid, rating,food_comment FROM Review WHERE cid = :id_bv";

      $stid = oci_parse($con, $query);
      oci_bind_by_name($stid, ":id_bv", $CID);
      oci_execute($stid);
      while ($row = oci_fetch_array($stid)):
          echo "<tr><td>" . htmlentities($row['RATING']) . "</td>";
          echo "<td>" . htmlentities($row['FOOD_COMMENT']) . "</td>";
          $reviewID = $row['RVID'];
      ?>
          <td>
              <form name="deleteReview" action="deletecomment.php" method="POST">
                  <input type="hidden" name="ID" value="<?php echo $reviewID; ?>"/>
                  <input type="submit" name="deleteReview" value="Delete"/>
              </form>
          </td>
          <?php
          echo "</tr>\n";
      endwhile;


      ?>
</table>
<?php
$query = "SELECT cid FROM customer c WHERE NOT EXISTS((SELECT rid FROM restaurant r)
                                         MINUS(SELECT rid FROM review rv WHERE rv.cid=c.cid))";

$stid_all=oci_parse($con,$query);
oci_execute($stid_all);

while ($row = oci_fetch_array($stid_all, OCI_ASSOC+OCI_RETURN_NULLS)) {
foreach ($row as $item) {
  if($item=$CID)echo "<div class='surprise'>CONGRATSSSS!!You have tried all the restaurants on HEYFOOD!</div>";
}

}


?>
  <form name="backToMainPage" action="searchindex.php">
      <input type="submit" value="Back To Main Page"/>
  </form>
</body>
</html>
