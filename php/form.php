<?php session_start(); ?>
<?php include("includes/head.php"); ?>
<?php

if (is_uploaded_file($_FILES["csvfile"]["tmp_name"])) {
    // If a file is successfully uploaded, move it from the temp dir to ../import/
    move_uploaded_file($_FILES["csvfile"]["tmp_name"], "../temp/" . $_FILES["csvfile"]["name"]);
    $_SESSION['uploadFileName'] = $_FILES["csvfile"]["name"];
    $uploadFileName = $_SESSION['uploadFileName'];
} else {
    echo include("includes/errorLoad.php");
    exit;
}
$openFile = fopen('../temp/' . $uploadFileName, 'r');
  $headers = fgetcsv($openFile, 0, ",", '"');
  foreach ($headers as $k => $header) {
    $headers[$k] = stringReplace($header);
    if( $header == '' ) {
      unset($headers[$k]);
    }
  }
  $headers = array_values($headers);
  $_SESSION['headers'] = $headers;
fclose($openFile);

// Initialization of a global variable
$_SESSION['cacheCount'] = 0;

if( empty($_SESSION[ 'gCount_'.$_SESSION['uploadFileName'] ]) ) {
  $_SESSION['gCount'] = count($headers) + 21;
} else {
  $_SESSION['gCount'] = $_SESSION[ 'gCount_'.$_SESSION['uploadFileName'] ];
}

if ( empty( $_SESSION['valuesArray_'.$_SESSION['uploadFileName']]) ) {
    include("includes/requiredValues.php");
    $_SESSION[ 'valuesArray_'.$_SESSION['uploadFileName'] ] = $requiredValues;
}

?>

<div class="container-fluid">
    <h2 class="pull-left"><strong>Step 2: </strong>Editing</h2>
    <h2 class="pull-right"><strong><?php echo $_SESSION['uploadFileName']; ?></strong></h2>
    <form action="csvgenerator.php" method="post">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Column Name:</th>
                <th></th>
                <th>Your Col Name:</th>
                <th>Your Col Value:</th>
                <th>Del/Add</th>
            </tr>
            </thead>
            <tbody id="form-container">
            </tbody>
        </table>

        <input type="hidden" name="gCount" id="gCount" />
        <button type="button" class="btn btn-danger btn-lg" onclick="deleteLastRow();">Delete Last</button>
        <button type="button" class="btn btn-success btn-lg" onclick="addFormRow();">Add Row</button>
        <button type="submit" class="btn btn-primary btn-lg pull-right" id="create-table">Generate Your Table</button>
    </form>


    <script>
        console.log("SESSION['gCount'] = <?php echo $_SESSION['gCount']; ?>");
        console.log("SESSION['cacheCount'] = <?php echo $_SESSION['cacheCount']; ?>");

        // Global counter
        var gCount = 0;

        // Ajax loading form rows
        function addFormRow() {
            $.ajax({
                async: false,
                url: 'includes/formRow.php',
                success: function (data) {
                    $('#form-container').append(data);
                    gCount++;
                    refreshForm();
                }
            });
        }

        // Generate the default number of rows
        var ittr = <?php echo $_SESSION['gCount']; ?> || 21;
        for (var i = 0; i < ittr; i++) {
            addFormRow();
        }

        //
        function refreshForm() {
            var rowNums = document.querySelectorAll("#form-container .row-num");
            var dropdownInputs = document.querySelectorAll("#form-container select.dropdownInput");
            var checkboxes = document.querySelectorAll("#form-container input.checkbox");
            var customInputs = document.querySelectorAll("#form-container input.customInput");
            var defaultValues = document.querySelectorAll("#form-container input.defaultValue");
            for (var i = 0; i < gCount; i++) {
                rowNums[i].innerHTML = i+1;
                dropdownInputs[i].setAttribute("name", "column[" + i + "][colName]");
                checkboxes[i].setAttribute("name", "column[" + i + "][checked]");
                customInputs[i].setAttribute("name", "column[" + i + "][customColName]");
                defaultValues[i].setAttribute("name", "column[" + i + "][defaultValue]");
            }
            document.getElementById("gCount").setAttribute("value", gCount);
            for (var j = 0; j < checkboxes.length; j++) {
                checkboxSwitch(checkboxes[j]);
            }
            console.log('Form updated!');
        }

        function addRow(el) {
            var elem = el.parentElement.parentElement;
            $.ajax({
                async: false,
                url: 'includes/formRow.php',
                success: function (data) {
                    $(data).insertAfter(elem);
                    gCount++;
                    refreshForm();
                }
            });
        }

        function deleteFormRow(el) {
            var row = el.parentNode.parentNode;
            row.parentNode.removeChild(row);
            gCount--;
            refreshForm();
        }

        function deleteLastRow() {
            document.getElementById("form-container").lastChild.remove();
            gCount--;
        }

        // Checkbox switch inputs disabled
        function checkboxSwitch(elem) {
            var checkboxes = document.querySelectorAll("input.checkbox");
            var index = Array.prototype.indexOf.call(checkboxes, elem);
            document.querySelectorAll("#form-container select.dropdownInput")[index].disabled = elem.checked;
            document.querySelectorAll("#form-container input.customInput")[index].disabled = !elem.checked;
            document.querySelectorAll("#form-container input.defaultValue")[index].disabled = !elem.checked;
        }

        console.log('JS gCount = ' + gCount);
    </script>

</div>
</body>
</html>
