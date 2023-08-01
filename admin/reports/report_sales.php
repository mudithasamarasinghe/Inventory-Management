<?php
// Initialize default date range
$start_date = null;
$end_date = null;

// Check if the form is submitted with date range values
if (isset($_POST['submit'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
}

$dataPoints = array();
// Handling connection to the database
try {
    $link = new \PDO('mysql:host=localhost;dbname=medix_inventory;charset=utf8mb4', 'root', '', array(
        \PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_PERSISTENT => false
    ));

    // Modify the SQL query to include the date range condition
    $query = 'SELECT i.name, t.quantity FROM sales_list s
              INNER JOIN stock_list t ON s.stock_ids = t.id
              INNER JOIN items i ON i.id = t.item_id group by i.name';

    // Add date range condition to the query if provided
    if ($start_date && $end_date) {
        $query .= ' WHERE s.date_created BETWEEN :start_date AND :end_date';
    }

    $handle = $link->prepare($query);

    // Bind the date range values to the query
    if ($start_date && $end_date) {
        $handle->bindParam(':start_date', $start_date);
        $handle->bindParam(':end_date', $end_date);
    }

    $handle->execute();
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);

    foreach ($result as $row) {
        array_push($dataPoints, array("label" => $row->name, "y" => $row->quantity));
    }
    $link = null;
} catch (\PDOException $ex) {
    print($ex->getMessage());
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <script>
        window.onload = function () {
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                theme: "light1", // "light1", "light2", "dark1", "dark2"
                title: {
                    text: "Item vs. Quantity Sold"
                },
                axisX: {
                    title: "Item Name"
                },
                axisY: {
                    title: "Quantity Sold"
                },
                data: [{
                    type: "column", //change type to bar, line, area, pie, etc
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

            // Function to update the chart with new data based on the selected date range
            function updateChart() {
                var startDate = document.getElementById('start_date').value;
                var endDate = document.getElementById('end_date').value;
                // Add your code to fetch new data from the database based on the selected date range
                // and update the dataPoints array
                // For example, you can use AJAX to make a request to the server and update the chart data
                // Then, re-render the chart using chart.render()
            }

            // Event listener to update the chart when the date range is changed
            document.getElementById('date_range_form').addEventListener('change', function () {
                updateChart();
            });
        }
    </script>
</head>
<body>
<h1>Item vs. Quantity Sold</h1>
<!-- Add the date range selector form -->
<form id="date_range_form" action="" method="post">
    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
    <input type="submit" name="submit" value="Generate Chart">
</form>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>
