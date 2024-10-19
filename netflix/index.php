<?php 

  session_start();
  if($_SESSION['loggedin'] !== true ){
    header('Location:login.php');
  }

  include_once("data.inc.php");
  
  
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>IMDFlix</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  
  <div id="netflix">
  <?php include_once("nav.inc.php"); ?>

  <div class="collection">
    
    <?php foreach($collection as $key => $c): ?>
      <a href="details.php?id=<?php echo $key ?>" class="collection__item" style="background-image: url('<?php echo $c["poster"]?>')">
      </a>
      <?php endforeach ?>   
   
  </div>
  
</div>

</body>
</html>
