<?php
$requiredValues = [
                ['checked' => 1, 'customColName' => 'sku', 'defaultValue' => 'name'],
                ['colName' => 'name'],
                ['checked' => 1, 'customColName' => 'has_options', 'defaultValue' => '0'],
                ['checked' => 1, 'customColName' => 'store_id', 'defaultValue' => 'admin'],
                ['checked' => 1, 'customColName' => 'category_ids', 'defaultValue' => ''],
                ['checked' => 1, 'customColName' => 'attribute_set', 'defaultValue' => str_replace('.csv', '', $_SESSION['uploadFileName'])],
                ['checked' => 1, 'customColName' => 'websites', 'defaultValue' => 'base'],
                ['checked' => 1, 'customColName' => 'qty', 'defaultValue' => 1],
                ['checked' => 1, 'customColName' => 'type', 'defaultValue' => 'simple'],
                // in the end
                ['checked' => 1, 'customColName' => 'short_description', 'defaultValue' => ''],
                ['checked' => 1, 'customColName' => 'description', 'defaultValue' => ''],
                ['checked' => 1, 'customColName' => 'thumbnail', 'defaultValue' => '' ],
                ['checked' => 1, 'customColName' => 'image', 'defaultValue' => '' ],
                ['checked' => 1, 'customColName' => 'small_image', 'defaultValue' => '' ],
                ['checked' => 1, 'customColName' => 'status', 'defaultValue' => 'Enabled' ],
                ['checked' => 1, 'customColName' => 'company', 'defaultValue' => '' ],
                ['checked' => 1, 'customColName' => 'weight', 'defaultValue' => 1 ],
                ['checked' => 1, 'customColName' => 'visibility', 'defaultValue' => 'Catalog, Search' ],
                ['checked' => 1, 'customColName' => 'price', 'defaultValue' => 1 ],
                ['checked' => 1, 'customColName' => 'tax_class_id', 'defaultValue' => 'None' ],
                ['checked' => 1, 'customColName' => 'is_in_stock', 'defaultValue' => 1 ]];

foreach ($headers as $key => $header) {
  $tableColValues[$key] = ['colName' => $header];
}

array_splice($requiredValues, 9, 0, $tableColValues);
