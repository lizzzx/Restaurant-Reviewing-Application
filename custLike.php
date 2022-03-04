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

<!DOCTYPE HTML>
<html>
    <head>
      <title>Your Liked Food</title>
        <link rel="stylesheet" href="includes/style.css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
      <h1>Your Liked Food</h1>

    <?php
    //Food information in liked list
        echo "<div class=\"intro\">The following is your liked foods. Click 'GO' to see more about the restaurant.<br></div>";
        $CID=3;
        $con = oci_pconnect("ora_zinniali", "a88978812", "dbhost.students.cs.ubc.ca:1522/stu");
        $stid0 = oci_parse($con, "SELECT distinct f.name, f.price, f.rid FROM Food f, CustomerLikes cl WHERE cl.cid =$CID AND f.rid = cl.rid AND f.fid = cl.fid");

    oci_execute($stid0);
    
    while ($row = oci_fetch_array($stid0)):
        $name = $row['NAME'];
        $price = $row['PRICE'];
        $rid = $row['RID'];
        echo "<div class=\"resname\">" . htmlentities($row['NAME']) . htmlentities($row['PRICE']). "</div>";
    ?>
            <form name="gotoResInfo" action="res.php" method="POST">
                <input type="hidden" name="RID" value="<?php echo $rid; ?>"/>
                <input type="submit" name="goto" value="GO"/>
            </form>

        <?php

    endwhile;
    oci_free_statement($stid);
?>

//<?php
//    // Find the most ordered tag
//    $stid1 = oci_parse($conn, "SELECT kf.description, MAX(COUNT(*)) FROM (SELECT kf.description, COUNT(*) AS likedCount FROM (SELECT distinct kf.fid, kf.rid, kf.description FROM KeywordOfFood kf, CustomerLike cl WHERE cl.cid =$CID AND cl.rid = kf.rid AND cl.fid = kf.fid) GROUP BY kf.description)");
//    oci_execute($stid1);
//    oci_define_by_name($stid1, 'KF.DESCRIPTION', $tag);
//    while (oci_fetch_array($stid1)) {
//        echo "<div class=\"resname\">", htmlentities($tag) . "</div>";
//    }
//    // Recommend food having the tag in the highest-rated restaurant
//    $stid_res = oci_parse($conn, "SELECT rid, AVG(rating) AS avgrate FROM Review GROUP BY rid ORDER BY avgrate DESC");
//    oci_execute($stid_res);
//    //provide food recommendation
//?>
  <form name="backToMainPage" action="searchindex.php">
      <input type="submit" value="Back To Main Page"/>
  </form>
</body>
</html>





