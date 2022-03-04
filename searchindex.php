<style>
.info {
    font-weight: bold;
    font-size: 1.5rem;
    margin-top: 5px;
    padding: 5px;
    background-color: #f07167;
    text-align: center;
    width: auto;
    color: #ecf8f8;
}
.Ginfo {
    background-color: #d6efc7;
      color: black;
}


</style>

<?php

$logonSuccess = false;
$mlogonSuccess = false;

// verify user's credentials
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $con = oci_connect("ora_zinniali", "a88978812", "dbhost.students.cs.ubc.ca:1522/stu");
  if (!$con) {
      $m = oci_error();
      exit('Connect Error ' . $m['message']);
  }

      $query = "SELECT cid,password FROM Customer WHERE cid = TO_NUMBER(:id_bv) AND password =TO_NUMBER(:pwd_bv)";
      $stid = oci_parse($con, $query);
      $id = $_POST['user'];
      $password = $_POST['userpassword'];
      oci_bind_by_name($stid, ':id_bv', $id);
      oci_bind_by_name($stid, ':pwd_bv', $password);
      oci_define_by_name($stid, 'CID', $cid);
      oci_define_by_name($stid, 'PASSWORD', $pass);

      oci_execute($stid);

      if (oci_fetch($stid)&& $cid=$id) {

        $logonSuccess=true;
        echo "<div class=\"Ginfo\"><br>User Login Successful</div>";

      }else{
        $logonSuccess=false;
          echo "<div class=\"info\"><br>User Login failed</div>";
        }

if ($logonSuccess == true) {?>
   <form action="editAcc.php" method="POST" name="gotoUser">
     <input type="hidden" name="cid" value="<?php echo $id ?>" />
      Go to edit account information and check coupons you have: <input type="submit" value="GO TO ACCOUNT INFO" />
  </form>
  <form action="custLike.php" method="POST" name="gotoLike">
    <input type="hidden" name="cid" value="<?php echo $id ?>" />
     Check out the foods you liked: <input type="submit" value="GO TO YOUR LIKED" />
 </form>
<?php

}

//for manager

      $querym = "SELECT mid,password FROM Manager WHERE mid = TO_NUMBER(:mid_bv) AND password =TO_NUMBER(:mpwd_bv)";
      $stidm = oci_parse($con, $querym);
      $idm = $_POST['manager'];
      $passwordm = $_POST['managerpassword'];
      oci_bind_by_name($stidm, ':mid_bv', $idm);
      oci_bind_by_name($stidm, ':mpwd_bv', $passwordm);
      oci_define_by_name($stidm, 'MID', $result_mid);
      oci_define_by_name($stidm, 'PASSWORD', $result_mpass);

      oci_execute($stidm);

      if (oci_fetch($stidm)&& $idm=$result_mid) {
        $mlogonSuccess=true;
        echo "<div class=\"Ginfo\"  ><br>Manager Login Successful</div>";
      }else{
        $mlogonSuccess=false;
          echo "<div class=\"info\"><br>Manager Login failed</div>";
        }

if ($mlogonSuccess == true) {?>
   <form action="manager.php" method="POST" name="gotoManages">
     <input type="hidden" name="rid" value="<?php echo $idm ?>" />
      Go to your restaurant by clicking: <input type="submit" value="<?php echo $idm ?>" />


  </form>
<?php
}

}


?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>HEYFOOD REVIEW</title>
        <link rel="stylesheet" href="includes/style.css">

    <!-- Font Awesome Icon -->
      <script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>

    </head>
    <body>
      <i class="fas fa-utensils"></i><h1>HEYFOOD REVIEW</h1>

        <form  action="connect.php" method="GET" name="resultList">
        Search by food name <br>  <input class="mainbox" type="text" name="word"/  >
            <input type="submit" value="Go" />

            <br><br>  <br><br>
            Still don't have an account for HEYFOOD?! <a href="createNewCustomer.php">Create now</a>
        </form>
<br><br>
<div class="line_01">ᗦ↞◃  ---  ᗦ↞◃ ---  ᗦ↞◃</div>
<br><br><br><br>Login to edit your account information and check the COUPONS you have!
<div class="form"  ><br>
        <form name="logon" action="searchindex.php" method="POST" >

            Username: <input type="number" name="user"/>
            Password:  <input type="password" name="userpassword"/>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                if (!$logonSuccess)
                    echo "Invalid account number and/or password";
            }
            ?>
            <input type="submit" value="Login"/>
        </form>



        <form name="logon" action="searchindex.php" method="POST" >
          If you are a manager-->
            Manager Account : <input type="number" name="manager"/>
            Password:  <input type="password" name="managerpassword"/>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                if (!$mlogonSuccess)
                    echo "Invalid account number and/or password";
            }
            ?>

            <input type="submit" value="Login"/>
        </form>
</div>

    </body>
</html>
