<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
<input type="file" name="csv" value="" />
<input type="submit" name="submit" value="Submit" /></form>

<?php

require_once('CsvParse.php');

// check post request
if ( isset($_POST["submit"]) ) {
    if ( isset($_FILES["csv"])) {
        // if there was an error uploading the file
        if ($_FILES["csv"]["error"] > 0) {
            echo "Return Code: " . $_FILES["csv"]["error"] . "<br />";
        } else {
            $name = $_FILES['csv']['name'];
            $ext = strtolower(end(explode('.', $_FILES['csv']['name'])));
            $tmpName = $_FILES['csv']['tmp_name'];
            // check extwnsion for file
            if($ext === 'csv'){
                // get cals from csv
                $cals = array_map('str_getcsv', file($tmpName));
                // show table with data
                $csvParse = new CsvParse();
                $data = $csvParse->getData($cals);
            } else {
                echo "Wrong file extension";
            }
        }
    } else {
        echo "No file selected";
    }
}

?>

<?php if($data): ?>
    <table>
        <tr>
            <th>CustomerId</th>
            <th>Number of calls within the same continent</th>
            <th>Total Duration of calls within the same continent</th>
            <th>Total number of all calls</th>
            <th>The total duration of all calls</th>
        </tr>
    <?php foreach($data as $customer_id=>$customer_data): ?>
        <tr>
            <td><?=$customer_id;?></td>
            <?php foreach($customer_data as $value): ?>
                <td><?=$value;?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>