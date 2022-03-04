<style>
table{
  margin: 10px 90px;
}
td,th{
  text-align: center;
  font-size: 1.5rem;
  margin-top: 20px;
    color: #1d3557;
}
.resname{
    color: #1d3557;
    font-size: 5rem;
    margin: 10px 10px;
}
.res{
    color: #247ba0;
    font-size: 1.8rem;
    margin: 10px 40px;
}
.rate{
  margin: 3px 150px;
  font-size: 5rem;
}
</style>


<?php
$idIsUnique = true;
$RIsInRange = true;
$RIsEmpty = false;
// Create connection to Oracle

$conn = oci_pconnect("ora_zinniali", "a88978812", "dbhost.students.cs.ubc.ca:1522/stu");
if (!$conn) {
   $m = oci_error();
   echo $m['message'], "\n";
   exit;
}
else {
   print "Hey!";
}

session_start();
$uri = $_SERVER['PHP_SELF'] . "?" . SID;
if( isset($_POST['dbuser']) ) {
	$dbuser = $_POST['dbuser'];
}
if( isset($_POST['pass']) ) {
	$pass = $_POST['pass'];
}

//echo "HERE IS THE POST "  .$_POST['RID']."<br>";//for debug
$thisresID=$_POST['RID'];

  //query for restautant basic information
  $stid0 = oci_parse($conn, "SELECT rid,name,rw.website,phone,ro.date_opened,ro.total_year
     FROM  Restaurant r, ResWebsite rw, ResOpenYear ro
     WHERE rid=TO_NUMBER(:rid_bv) AND r.website=rw.website
     AND TO_CHAR(ro.date_opened,'DD-MON-YY')=TO_CHAR(r.date_opened,'DD-MON-YY')");

  oci_bind_by_name($stid0, ':rid_bv', $thisresID);

  oci_define_by_name($stid0, 'NAME', $resname);
  oci_define_by_name($stid0, 'PHONE', $phone);
  oci_define_by_name($stid0, 'WEBSITE', $website);
  oci_define_by_name($stid0, 'DATE_OPENED', $d_o);
  oci_define_by_name($stid0, 'TOTAL_YEAR', $total_y);

 oci_execute($stid0);

      while (oci_fetch($stid0)) {

        echo "<div class=\"resname\"> $resname <br>\n</div>";
        echo "<div class=\"res\"> Restaurant phone number: $phone <br> </div>\n";
        echo "<div class=\"res\"> Restaurant website: $website <br> </div>\n";
        echo "<div class=\"res\"> Date opened: $d_o <br> </div>\n";
        echo "<div class=\"res\"> Opened years: $total_y <br> </div>\n";
      }



$stid_addr = oci_parse($conn, "SELECT  aone.house_num, aone.street, atwo.postal_code, atwo.city, atwo.province
         FROM  AddressLineTwo atwo, AddressLineOne aone
         WHERE aone.postal_code=atwo.postal_code AND aone.rid=TO_NUMBER(:rid_bv)");

      oci_bind_by_name($stid_addr, ':rid_bv', $thisresID);
      oci_define_by_name($stid_addr, 'POSTAL_CODE', $pc);
      oci_define_by_name($stid_addr, 'HOUSE_NUM', $hnum);
      oci_define_by_name($stid_addr, 'STREET', $str);
      oci_define_by_name($stid_addr, 'PROVINCE', $province);
      oci_define_by_name($stid_addr, 'CITY', $city);

      oci_execute($stid_addr);
      while (oci_fetch($stid_addr)) {
            echo "<div class=\"res\">Address: $hnum $str, $pc, $city, $province   <br></div>\n";
            echo " <br>\n";
          }




$stid_avg = oci_parse($conn, "SELECT AVG(rating) AS avgrate
               FROM Review GROUP BY rid HAVING rid=$thisresID");
  //  oci_bind_by_name($stid_addr, ':rid_bv', $thisresID);
 oci_define_by_name($stid_avg, 'AVGRATE', $num);
 oci_execute($stid_avg);
 while (oci_fetch($stid_avg)){
       echo "<p class=\"res\">  Average rating:</p><p class='rate'>  $num </p>";
     }


//comments

$stid = oci_parse($conn, "SELECT rating, food_comment,comment_date
        FROM  Review rv
        WHERE rid=$thisresID");
        oci_define_by_name($stid, 'RATING', $rating);
        oci_define_by_name($stid, 'FOOD_COMMENT', $comment);
        oci_define_by_name($stid, 'COMMENT_DATE', $date);
    oci_execute($stid);
    print "<p class='resname'>Customer reviews:</p>\n";
    //for restaurant name and hyperlink to res info and comments page
    print "<table style=width:70% cols=3 border=2>\n";
    print "<tr>\n";
    print "<th>Rating</th>\n";
    print "<th>Comments</th>\n";
    print "<th>Date</th>\n";
    print "</tr>";
    while (oci_fetch($stid)) {
      echo "<tr>\n";
         echo "   <td> $rating </td>\n";
        echo "    <td> $comment </td>\n";
        echo "    <td>  $date </td>\n";
        echo "</tr>\n";
      }
  echo "</table>\n";


    $sql = "SELECT p.pid,p.url from photo p, review r where p.rvid=r.rvid AND r.rid=$thisresID";
    $result = oci_parse($conn,$sql);
    oci_define_by_name($result, 'PID', $pid);
    oci_define_by_name($result, 'URL', $url);
    oci_execute($result);
    echo "<div class='resname'><br>Photos in review:</div>";
    while (oci_fetch($result)) {
      //echo " $pid <br>\n";
      echo "<div class='res'><a href='photo/$url'>Click to see photo $pid</a><br></div>";

    }


  ?>

  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
  <html>
      <head>
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <title>Restaurant</title>
      </head>
      <body>
        <br>





      </body>
  </html>
