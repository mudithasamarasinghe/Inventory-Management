<?php
$dataPoints = array();
//Handling connection to database
try {
    $link = new \PDO('mysql:host=localhost;dbname=medix_inventory;charset=utf8mb4', 'root', '', array(
        \PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_PERSISTENT => false
    ));

    $handle = $link->prepare('SELECT i.name AS item_name, s.amount FROM sales_list AS s 
                             INNER JOIN stock_list AS t ON s.stock_ids = t.id 
                             INNER JOIN items AS i ON t.item_id = i.id');
    $handle->execute();
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);

    foreach ($result as $row) {
        array_push($dataPoints, array("label" => $row->item_name, "y" => $row->amount));
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

        }
    </script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>
