<style>
.intro {
    font-weight: bold;
    font-size: 2rem;
    margin-top: 5px;
    padding: 5px;
    text-align: left;
    color: #black;
    margin: 10px 50px;
}
.resname{
    text-align: center;
    font-size: 1.8rem;
    margin-top: 20px;
}
input[type=submit] {
  background-color: #46494c;
  border: none;
  color: white;
  padding: 12px 20px;
  text-decoration: none;
  margin: 5px 800px;
  cursor: pointer;
  border-radius:15px;
}
table{
  margin: 10px 90px;
}
td,th{
  text-align: center;
  font-size: 1.5rem;
  margin-top: 20px;
}
</style>


<?php
// Create connection to Oracle
$conn = oci_pconnect("ora_zinniali", "a88978812", "dbhost.students.cs.ubc.ca:1522/stu");
if (!$conn) {
   $m = oci_error();
   echo $m['message'], "\n";
   exit;
}
else {
   print "<div class=\"intro\">:) Hi <br> </div>";
}

session_start();
$uri = $_SERVER['PHP_SELF'] . "?" . SID;
if( isset($_POST['dbuser']) ) {
	$dbuser = $_POST['dbuser'];
}
if( isset($_POST['pass']) ) {
	$pass = $_POST['pass'];
}

  // prepare the query for restautant with searched food
  echo "<div class=\"intro\">The following restaurants have the food you searched!<br>
  Click 'GO' to see more about the restaurant.<br></div>";
  $stid0 = oci_parse($conn, "SELECT distinct  r.rid, rw.name
     FROM Food f, Restaurant r,ResWebsite rw
     WHERE f.name LIKE :text0_bv AND r.website=rw.website AND r.rid=f.rid ");
     $text = '%'.$_GET['word'].'%';

oci_bind_by_name($stid0, ":text0_bv", $text);
oci_execute($stid0);

while ($row = oci_fetch_array($stid0)):
    echo "<div class=\"resname\">" . htmlentities($row['NAME']) . "</div>";
    $resID = $row['RID'];
?>
        <form name="gotoResInfo" action="res.php" method="POST">
            <input type="hidden" name="RID" value="<?php echo $resID; ?>"/>
            <input type="submit" name="goto" value="GO"/>
        </form>

    <?php

endwhile;
oci_free_statement($stid);
?>

</table>

<?php
  // prepare the query for presenting table with food name, price, restautant phone and website
  $stid = oci_parse($conn, "SELECT f.name, f.price,  r.phone, rw.website
          FROM Food f, Restaurant r,ResWebsite rw
          WHERE f.name LIKE :text_bv AND r.website=rw.website AND r.rid=f.rid ");
 $text = '%'.$_GET['word'].'%';
  oci_bind_by_name($stid, ':text_bv', $text);
  oci_define_by_name($stid, 'NAME', $name);
  oci_define_by_name($stid, 'PRICE', $price);
  oci_define_by_name($stid, 'PHONE', $phone);
  oci_define_by_name($stid, 'WEBSITE', $website);
// execute the query
if( !oci_execute($stid)  ) {
    $e = oci_error();
    echo htmlentities($e['message'], ENT_QUOTES);
	oci_close($conn);
} else {
	// retrieve the results
    oci_execute($stid);
    $row = oci_fetch_array($stid);
    if (!$row) {
    echo "<div class=\"intro\">There is no such food with keyword   " . $_GET['word'] . "</div>";
    } else {
    oci_execute($stid);
      print "<p class=\"intro\"><br><br>The search results with entered keywords are as follows:</p>\n";
      print "<table style=width:70% cols=4 border=2>\n";
      print "<tr>\n";
      print "<th>Food</th>\n";
      print "<th>Price</th>\n";
      print "<th>Restaurant Phone Number</th>\n";
    print "<th>Restaurant Website</th>\n";
      print "</tr>";

    while ($row = oci_fetch_array($stid)):
        echo "<tr>\n";
        echo "<td>" . htmlentities($row['NAME']) . "</td>\n";
        echo "<td>" . htmlentities($row['PRICE']) . "</td>\n";
        echo "<td>" . htmlentities($row['PHONE']) . "</td>\n";
        echo "<td>" . htmlentities($row['WEBSITE']) . "</td>\n";
        echo "</tr>\n";
    endwhile;}


	oci_close($conn);
	echo "</table>\n";
}

?><!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Search Result</title>
    <!-- Bootstrap Scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </head>
  </head>
  <body>

  </body>
</html>
