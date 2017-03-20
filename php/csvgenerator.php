<?php session_start(); ?>
<?php include("includes/head.php"); ?>

<?php

// Get values from $_POST
$arrFormItems = array();
foreach ($_POST["column"] as $post) {
    $arrFormItems[] = $post;
}

$uploadFileName = $_SESSION['uploadFileName'];
$openFile = fopen('../temp/' . $uploadFileName, 'r');
$headers = fgetcsv($openFile, 0, ",", '"');
$products = array();
$skus = array();
while (($line = fgetcsv($openFile, 0, ",", '"')) !== FALSE) {
    $line = array_combine($headers, $line);
    $sku = $line['Name'];
    if (isset($products[$sku])) {
        echo "<br />Double sku: $sku = old sku" . $line['Code'];
    }
    $line['sku'] = $sku;
    if (strlen($sku) >= 64) {
        echo "<br>" . $sku . ' ===== length ' . strlen($sku);
        if (mb_strlen($sku, 'UTF-8') >= 64) {
            echo ' == MB length ' . mb_strlen($sku, 'UTF-8');
        }
    }
    $products[$sku] = $line;
}
$headers = $_SESSION['headers'];
fclose($openFile);


/* EXPORT TABLE */
$exports = array();
$attrValues = array(); // array of the export-table values
$pdfArr = []; // array of 'pdf' files
foreach ($products as $sku => $product) {

    foreach ($product as $k => $v) {
        $key = $k;
        $key = stringReplace($key);
        $product[$key] = trim($v);
        if ( $k !== $key ){
            unset($product[$k]);
        }
    }

    foreach ($arrFormItems as $item) {

        if ( isset($item['colName']) ) {
            $export[$item['colName']] = importCkeckOf ( $product[$item['colName']] );
         }
        if ( isset($item['customColName']) ) {
            $colName = stringReplace( $item['customColName'] );
            $colValue = $item['defaultValue'];
            foreach ($headers as $header) {
                if ($header == $item['defaultValue']) {
                    $colValue = $product[$header];
                }
            }
            $export[$colName] = importCkeckOf ( $colValue );
        }

    }


//========================= SANDBOX =======================================

    //$skuStr = str_replace(['ingle', ' SRT TA, ', 'with ','table',' Preloaded', ' Ball Nut,', 'Preloaded,  ','PowerTrac ', 'Circuit', 'ernal Return','Circuit, '], ' ', $sku) ;
    //$skuStr = preg_replace('|\s+|', '', $skuStr);
    $skuStr = uniqid('', true);
    $skuStr =  $product['name'] . '_' . substr($skuStr, 9);

    $export['sku'] = stringReplace( $skuStr); // . str_replace('micromo', '', $product['attribute_set']);


    $img = '/' . $product['image'];
    $export['image'] = $product['image'] ? $img : '';
    $export['thumbnail'] = $product['image'] ? $img : '';
    $export['small_image'] = $product['image'] ? $img : '';
/*
    $export['image2'] = $product['image_1'] ? '/' . $product['image_1'] : '';
    $export['image3'] = $product['image_2'] ? '/' . $product['image_2'] : '';
    $export['image4'] = $product['image_3'] ? '/' . $product['image_3'] : '';
*/

    //$images = str_replace("/", "", $product['image']);
    //$arr = explode(";", $images);

    //$export['image'] = $arr[0] ? '/' . $arr[0] : '';
    //$export['image2'] = $arr[1] ? '/' . $arr[1] : '';
    //$export['thumbnail'] = $arr[0] ? '/' . $arr[0] : '';
    //$export['small_image'] = $arr[0] ? '/' . $arr[0] : '';

  //$export['price'] = $product['price'] ? str_replace(['$', '.00'], '', $product['price']) : 1;
/*
  $shortDesc = $product['description'];
  $shortDesc = str_replace('<p></p>', "", $shortDesc);
  $shortDesc = str_replace('<table></table>', "", $shortDesc);
  $shortDesc= preg_replace('|\s+|', ' ', $shortDesc);
  $shortDesc = trim($shortDesc);*/
  //$description = str_replace("<br>", "\n", $product['description']);

  $shortDesc = $product['description'] . "\n\n" .$product['features'];
  $export['short_description']  = $shortDesc;// $shortDesc; importCkeckOf ( $product['description'] ) . "\n\n" . $shortDesc;
  $export['description'] = $shortDesc;



//========================= END sandbox ========================================

  // Array with values from "Export table"
  foreach ($export as $k => $v) {
      $attrValues[$k][] = $v;

      // Array of columns that contain 'pdf' file
      if( strpos($v, '.pdf') !== false) {
          $pdfArr[] = $k;
      }
  }

  // Add line in the table
  $exports[] = $export;

  //$requiredAttributes = ['sku', 'type', 'attribute_set', 'name', 'description', 'short_description', 'weight', 'price', 'tax_class_id'];

}
//=============================================
$pdfArr = array_unique($pdfArr);
echo '<pre>';
echo print_r($pdfArr);
echo '<pre>'; // <=============================

foreach ($attrValues as $k => $v) {
    $attrValues[$k] = array_unique($v);
    asort($attrValues[$k]);
}


/* SAVE PRODUCTS */
$outputBuffer = fopen("../temp/import-" . $uploadFileName, 'w');
fputcsv($outputBuffer, array_keys(reset($exports)), ',', '"');
foreach ($exports as $val) {
    fputcsv($outputBuffer, $val, ',', '"');
}
fclose($outputBuffer);


// Save number of form rows in cache, for next session
$_SESSION[ 'gCount_'.$_SESSION['uploadFileName'] ] = $_POST['gCount'];
// Save values from inputs in cache, for next session
$_SESSION[ 'valuesArray_'.$_SESSION['uploadFileName'] ] = $arrFormItems;

?>

<div class="container" style="width: 500px;">
    <h3><strong>Step 3: Success! </strong></h3>
    <div class="row alert alert-success">
        <div>
            <p class="help"><strong>Table successfully created.</strong></p>
            <p class="help">You can find it in the <?php echo '../ready/import-' . $_SESSION['uploadFileName']; ?>.</p>
        </div>
        <a href="../index.php" class="btn  btn-success"><strong>Go to Step 1</strong> and create another table...</a>
    </div>
</div>

<?php
    echo '<br /><strong>products: ' . (count($products)) . '</strong>';
    echo "<pre><u><strong>first product, for example: </strong></u><br />";
    echo print_r($exports[1]);
    echo "</pre>";

    echo "<pre><u><strong>unique attributes: </strong></u><br />";
    echo print_r($attrValues);
    echo "</pre>";
?>

</body>
</html>
