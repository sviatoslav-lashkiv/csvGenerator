<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Upload</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
</head>
<body>

<div class="container" style="width: 500px;">
    <h2><strong>Step 1: </strong></h2>
    <div class="row alert alert-info">
        <form method="post" enctype="multipart/form-data" action="form.php">
            <h3>Select tables...</h3>
            <div id="form-container">
            </div>
            <button type="button" class="btn btn-danger" onclick="delInput();">-</button>
            <button type="button" class="btn btn-success" onclick="addInput();">+</button>
            <button type="submit" class="btn btn-lg btn-primary pull-right">Go >>></button>
        </form>
    </div>
</div>

<script>
  var formContainer = document.getElementById('form-container');
  var count = 0;
  function addInput () {
    count++;
    var div = document.createElement('div');
    div.className = 'form-group';
    div.innerHTML = '<input type=\"file\" name=\"csvfile' + count + '\">';
    formContainer.appendChild(div);
  }
  function delInput () {
    formContainer.lastChild.remove();
    count--;
  }
addInput ();
addInput ();
addInput ();



</script>

</body>
</html>
