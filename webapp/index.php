<?php
/*
** Mancave Webapp
** Coded by Tamer
** 06-12-2017
*/

// Include the Mancave class.
require_once('../mancave.class.php');

// Do some authentication, to prevent unauthorized access.
if ($_GET['access'] != 'strongpasswordhere') {
  die('auth error');
}

// Check for posts from the app.
if ($_POST) {

  // Start the Mancave controller object.
  $c = new Mancave_Controller();

  // If it's a percentage request do this.
  if (isset($_POST['percentage']) && is_numeric($_POST['procent'])) {
    $alert = 'Rolluik wordt op '.$_POST['procent'].'% gezet...';
    $c->devices_Control('675200317', $_POST['procent']);
  }

  // If it's an open request do this.
  if (isset($_POST['open'])) {
    $alert = 'Rolluik wordt maximaal geopend.';
    $c->devices_Control('675200317', 0);
  }

  // If it's a close request do this.
  if (isset($_POST['close'])) {
    $alert = 'Rolluik wordt maximaal gesloten.';
    $c->devices_Control('675200317', 100);
  }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="apple-touch-icon" href="icon.png">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <link rel="manifest" href="manifest.webmanifest">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>Rolluikapp</title>

  <style>
    /* Sticky footer styles
    -------------------------------------------------- */
    html {
      position: relative;
      min-height: 100%;
    }
    body {
      /* Margin bottom by footer height */
      margin-bottom: 60px;
    }
    .footer {
      position: absolute;
      bottom: 0;
      width: 100%;
      /* Set the fixed height of the footer here */
      height: 60px;
      background-color: #f5f5f5;
    }


/* Custom page CSS
-------------------------------------------------- */
/* Not required for template or sticky footer method. */

.container {
  width: auto;
  max-width: 680px;
  padding: 0 15px;
}
.container .text-muted {
  margin: 20px 0;
}
</style>

<!-- Bootstrap -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <div class="container">

      <div class="page-header">
        <h1>Rolluikapp <img src="icon.png" style="width: 30px;"></h1>
      </div>

      <?php
      if (!empty($alert)) {
        echo '<div class="alert alert-info" role="alert">'.$alert.'</div>';
      }
      ?>

      <form method="post">
        <div class="form-group">
          <label>Rolluik ID</label>
          <input type="text" class="form-control" value="675200317" disabled>
          <input type="hidden" name="id" value="675200317">
          <small class="form-text text-muted">ID nummer van de rolluik controller.</small>
        </div>
        <div class="form-group">
          <label>Sluitpercentage</label>
          <select name="procent" class="form-control">
            <option value="25">25%</option>
            <option value="50">50%</option>
            <option value="75">75%</option>
            <option value="99">99%</option>
          </select>
          <small class="form-text text-muted">Geef hier een percentage aan voor de rolluik stand.</small>
        </div>
        <div class="form-group"><input type="submit" name="percentage" value="Percentage" class="btn btn-info btn-lg"></div>
        <div class="form-group"><input type="submit" name="open" value="Openen" class="btn btn-success btn-lg"></div>
        <div class="form-group"><input type="submit" name="close" value="Sluiten" class="btn btn-danger btn-lg"></div>
      </form>
      <?php /*if (!empty($response)) { echo '<div class="col-md-8"><code>'.$response.'</code></div>'; } // Use this for debugging, it provides json output from the api. */ ?>
    </div>

  </div>
  <footer class="footer">
    <div class="container">
      <p class="text-muted text-center">Rolluikapp Concept by Qarizma</p>
    </div>
  </footer>


  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</body>
</html>
