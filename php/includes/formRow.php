<?php session_start(); ?>
<tr>
    <?php
    $gCount = $_SESSION['cacheCount'];
    $headers = $_SESSION['headers'];
    $valuesArray = $_SESSION[ 'valuesArray_'.$_SESSION['uploadFileName'] ];
    ?>
    <th class="row-num"></th>
    <th class="form-group">
        <select class="form-control input-sm dropdownInput">
            <?php
            foreach ($headers as $row) {
                if ($row == $valuesArray[$gCount]['colName']) {
                    echo "<option selected>" . $row . "</option>";
                } else {
                    echo "<option>" . $row . "</option>";
                }
            }
            ?>
        </select>
    </th>
    <th class="checkbox col-xs-2">
        <label>
            <input type="checkbox" class="checkbox" value="1" onchange="checkboxSwitch(this);"
                <?php echo isset($valuesArray[$gCount]['checked']) ? 'checked' : ''; ?> />switch
        </label>
    </th>
    <th class="form-group">
        <input type="text" class="form-control input-sm customInput"
               value="<?php echo isset($valuesArray[$gCount]['customColName']) ? $valuesArray[$gCount]['customColName'] : ""; ?>"
               placeholder="Custom Column Name"
        >
    </th>
    <th class="form-group">
        <input type="text"
               class="form-control input-sm defaultValue"
               value="<?php echo isset($valuesArray[$gCount]['defaultValue']) ? $valuesArray[$gCount]['defaultValue'] : ""; ?>"
               placeholder="Default Column Value"
        >
    </th>
    <th class="form-group">
        <button type="button" class="btn btn-sm btn-warning" onclick="deleteFormRow(this);">-</button>
        <button type="button" class="btn btn-sm btn-info" onclick="addRow(this);">+</button>
    </th>
    <?php $_SESSION['cacheCount'] = $_SESSION['cacheCount'] + 1; ?>
</tr>
