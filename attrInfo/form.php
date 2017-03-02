<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Atrributes and Attribute-sets</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <style>
        .hidden-block {display: none;}
    </style>
</head>
<body>

<?php

function stringReplace($str)
{
    $str = preg_replace("/[^a-zA-Z0-9\s]/", " ", $str);
    $str = trim($str);
    $str = mb_strimwidth($str, 0, 30, '');
    $str = preg_replace('|\s+|', '_', $str);
    $str = mb_strtolower($str);
    return $str;
}

//=====================================================================
// Generator csv-table for import product atrributes and attribute-sets
//=====================================================================

// For Filter attributes array
$bannedAttr = [
    'sku',
    'name',
    'category_ids',
    'has_options',
    'store_id',
    'customColName',
    'attribute_set',
    'websites',
    'qty',
    'type',
    'short_description',
    'description',
    'thumbnail',
    'image',
    'small_image',
    'status',
    //'company', // <<<=====
    'weight',
    'visibility',
    'price',
    'tax_class_id',
    'is_in_stock' ];


$allHeaders = array();
$allProducts = array();
$count = count($_FILES);

while (0 < $count) {
    $openFile = fopen($_FILES['csvfile' . $count]['tmp_name'], 'r');
    $headers = fgetcsv($openFile, 0, ",", '"');
    $allHeaders[$_FILES['csvfile' . $count]['name']] = $headers;
    $products = array();
    $skus = array();
    while (($line = fgetcsv($openFile, 0, ",", '"')) !== FALSE) {
        $line = array_combine($headers, $line);
        $sku = $line['name'];
        $line['sku'] = $sku;
        $products[$sku] = $line;
    }
    $allProducts[$_FILES['csvfile' . $count]['name']] = $products;
    fclose($openFile);
    $count--;
}

$lines = [  'attribute_code',
            'frontend_input',
            'frontend_label',
            'option_label',
            'attribute_sets_and_groups',
            'is_visible',
            'is_filterable',
            'is_comparable',
            'is_visible_on_front',
            'is_html_allowed_on_front' ];

$exports = [];
foreach ($allProducts as $key => $products) {
    foreach ($products as $kk => $product) {
        foreach ($product as $k => $v) {
            if ($v && !in_array($k, $bannedAttr)) {
                $count = [];
                $count['attribute_code'] = $k;
                $count['frontend_input'] = 'multiselect';
                $labelStr = $k;
                $labelStr = str_replace('_', ' ', $labelStr);
                $labelStr = ucwords($labelStr);
                $count['frontend_label'] = $labelStr;
                $count['option_label'] = preg_replace('|\s+|', ' ', $v);
                $count['attribute_sets_and_groups'] = $product['attribute_set'] . '/General';
                $count['is_visible'] = '1';
                $count['is_filterable'] = '1';
                $count['is_comparable'] = '1';
                $count['is_visible_on_front'] = '1';
                $count['is_html_allowed_on_front'] = '1';
                $exports[] = $count;
            }
        }
    }
}
$filteredArr = [];
foreach ($exports as $export) {
    if (!in_array($export, $filteredArr)) {
        $filteredArr[] = $export;
    }
}
$exports = $filteredArr;
sort($exports);
array_unshift($exports, $lines);

/* SAVE */
$outputBuffer = fopen('file.csv', 'w');
//fputcsv($outputBuffer, array_keys(reset($exports)), ',', '"');
foreach ($exports as $fields) {
    fputcsv($outputBuffer, $fields, ',', '"');
}
fclose($outputBuffer);

//============ END Script =============


// Filtered headers for attribute-sets
$filteredHeaders = [];
foreach ($allHeaders as $key => $headers) {
    $filteredHeader = [];
    foreach ($headers as $header) {
        if (!in_array($header, $bannedAttr)) {
            $filteredHeader[] = $header;
        }
    }
    $filteredHeaders[$key] = $filteredHeader;
}

?>


<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <a class="btn btn-link" onclick="toggle(this);"><strong>Unique Headers</strong></a>
            <div class='hidden-block'>
          <pre><?php
              $uniqueHeaders = array();
              foreach ($allHeaders as $headers) {
                  foreach ($headers as $k => $v) {
                      if ($v) {
                          $uniqueHeaders[] = stringReplace($v);
                      }
                  }
              }
              $uniqueHeaders = array_unique($uniqueHeaders);
              echo print_r($uniqueHeaders); ?>
          </pre>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <a class="btn btn-link" onclick="toggle(this);"><strong>All Attributes</strong></a>
            <div class='hidden-block'>
          <pre>
              <?php
              $arrAttributes = array();
              foreach ($allProducts as $products) {
                  foreach ($products as $k => $product) {
                      foreach ($product as $key => $v) {
                          if ($v) {
                              $arrAttributes[stringReplace($key)][] = trim($v);
                          }
                      }
                  }
              }
              echo print_r($arrAttributes);
              ?>
          </pre>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <a class="btn btn-link" onclick="toggle(this);"><strong>All Unique Attributes</strong></a>
            <div class='hidden-block'>
                <strong>Attributes:
                    <?php
                    $uniqueAttributes = array();
                    foreach ($arrAttributes as $k => $v) {
                        $uniqueAttributes[$k] = array_unique($v);
                    }
                    echo count($uniqueAttributes);
                    ?>
                </strong>
                <pre><?php echo print_r($uniqueAttributes); ?></pre>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <a class="btn btn-link" onclick="toggle(this);"><strong>Filtered and Sorted Unique Attribute Values</strong></a>
            <div class='hidden-block'>
                <strong>Attributes:
                    <?php
                    $filteredAttributes = [];
                    foreach ($uniqueAttributes as $k => $v) {
                        if (!in_array($k, $bannedAttr)) {
                            $sortVal = $v;
                            asort($sortVal);
                            $filteredAttributes[$k] = $sortVal;
                        }
                    }
                    echo count($filteredAttributes); ?>
                </strong>
                <pre><?php echo print_r($filteredAttributes); ?></pre>
            </div>
        </div>
    </div>
    <div class="row">
        <a class="btn btn-link" onclick="toggle(this);"><strong>Filtered headers for attribute-sets</strong></a>
        <div class="hidden-block">
            <?php
            foreach ($filteredHeaders as $file => $headers) {
                echo '<ul>';
                echo '<a class="btn btn-link" onclick="toggle(this);"><strong>' . $file . '</strong></a>';
                echo '<li class="hidden-block"><pre>';
                echo '<strong>' . str_replace(['import-', '.csv'], '', $file) . '</strong><br />';
                echo print_r($headers);
                echo '</li></pre></ul>';
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <a class="btn btn-link" onclick="toggle(this);"><strong> CSV-Table with Attributes/Sets name and
                    Options</strong></a>
            <div class='hidden-block'>
                <strong>Attributes: <?php echo count($exports); ?></strong>
                <pre><?php echo print_r($exports); ?></pre>
            </div>
        </div>
    </div>
</div>



<script>
    function toggle(el) {
        var elem = el.parentElement.getElementsByClassName('hidden-block')[0];
        elem.style.display = (elem.style.display == 'none') ? 'block' : 'none';
    }
</script>

</body>
</html>
