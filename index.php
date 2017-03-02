<?php session_start(); ?>
<?php include("php/includes/head.php"); ?>

<div class="container" style="width: 500px;">
    <h2><strong>Step 1: </strong></h2>
    <div class="row alert alert-info">
        <form method="post" enctype="multipart/form-data" action="php/form.php">
            <h3>Select table for editing.</h3>
            <div class="form-group">
                <input type="file" name="csvfile">
            </div>
            <button type="submit" class="btn btn-lg btn-info">Go and create your table...</button>
        </form>
    </div>
</div>

<!--div class="row">
  <?php
    foreach ($_SESSION as $k => $session) {
      echo '<div class="col-xs-3"><pre>';
      echo '<h5><strong>'. $k .'</strong></h5>';
      echo print_r($session);
      echo '</pre></div>';
    }
  ?>
</div-->

</body>
</html>
