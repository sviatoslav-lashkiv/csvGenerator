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
<?php
/* FUNCTIONS */

// Clean strings and bringing in lower case
function stringReplace ($str) {
  $str = preg_replace("/[^a-zA-Z0-9\s]/", " ", $str);
  $str = mb_strimwidth($str,0, 30, '');
  $str = trim($str);
  $str = preg_replace('|\s+|', '_', $str);
  $str = mb_strtolower($str);
  return $str;
}

function importCkeckOf ($str) {
  //$check = ["\"", "”", "'", "°", "º"];
  //$str = str_replace( $check, "", $str);
  $str = preg_replace('|\s+|', ' ', $str);
  $str = trim($str);
  return $str;
}
?>
