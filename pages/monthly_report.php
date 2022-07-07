<?php
    include '../includes/connection.php';
    include '../includes/controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
    <title>Report</title>
</head>
<body>

    <div class="container mt-5 pt-5">
        <h1 class="text-center">Monthly Report</h1>
        <div class="container text-center mt-5 d-flex justify-content-center">
            <!-- Total Sales Report -->
            <?php 
            $result = $query->query("
                SELECT 
                MONTHNAME(sales_date) as monthname,
                    SUM(total_sales) as amount
                FROM sales
                GROUP BY MONTH(sales_date)
            ");

            foreach($result as $data)
            {
                $month5[] = $data['monthname'];
                $amount5[] = $data['amount'];

            }
            //   var_dump($month);
            //   echo '<pre>';
            //   print_r( $amount);
            //   echo '/pre>';
            ?>
            <div class="w-25 mx-4">
                <canvas id="myChart5"></canvas>
                <?php 
                $total5 = 0;
                $result = $query->query("
                    SELECT SUM(total_sales) as sumofsales FROM sales
                ");
                    foreach ($result as $value) {
                        $total5 = implode(" ",$value);
                    }
                ?>
                    <label class="right">Total of Sales from <?php echo implode(", ",$month5) ?>: <?php echo $total5 ?> </label>
                    <a type="button" class="btn btn-outline-secondary btn-sm text-center mt-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        View Total of Sales
                    </a>
            </div>

            <!-- Customers Report -->
            <?php 

            $result = $query->query("
            SELECT 
                MONTHNAME(sales_date) as monthname,
                SUM(customers) as customers
            FROM sales
            GROUP BY MONTH(sales_date)
            ");

            foreach($result as $data)
            {
            $month4[] = $data['monthname'];
            $customer4[] = $data['customers'];

            }
            ?>

            <div class="w-25 mx-4">
                <canvas id="myChart4"></canvas>
                <?php 
                    $total4 = 0;
                    $result4 = $query->query("
                        SELECT SUM(customers) as sumCustomer FROM sales
                    ");
                        foreach ($result4 as $value) {
                            $total4 =implode(" ",$value);
                    }
                ?>
                    <label class="right">Total of Customers from <?php echo implode(", ",$month4) ?>: <?php echo $total4 ?> </label>
                    <a type="button" class="btn btn-outline-secondary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#exampleModal2">
                        View Total Number of Customers
                    </a>
                </div>
            <!-- Average -->
            <?php 
   
            $result = $query->query("
                SELECT 
                MONTHNAME(sales_date) as monthname,
                SUM(total_sales) as amount, 
                SUM(customers) as total,
                total_sales/customers AS 'Average'
                FROM sales
                GROUP BY monthname DESC
            ");

            foreach($result as $data)
            {
                $monthname6 [] = $data['monthname'];
                $total6 [] = $data['total'];
                $avg6 [] = $data['Average'];

            }
            ?>

            <div class="w-25">
                <canvas id="myChart6"></canvas>
                <?php 
                    $average = 0;
                    $result = $query->query("
                        SELECT SUM(customers) as sumCustomer, SUM(total_sales) as sumTotalsales FROM sales
                    ");

                        foreach($result as $data)
                        {
                            $custValue6 = $data['sumCustomer'];
                            $salesValue6 = $data['sumTotalsales'];


                            $average = $salesValue6 / $custValue6;

                            }
                ?>
                    <label class="right"> Average purchase per customer from <?php echo implode(", ",$monthname6) ?> : <?php echo ($average) ?> </label>
                    <a type="button" class="btn btn-outline-secondary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#exampleModal3">
                        View Average purchase per customer
                    </a>
            </div>


        </div>
    </div>


    <?php require_once 'total_of_sales.php' ?>
    <?php require_once 'customer.php' ?>
    <?php require_once 'average.php' ?>

<!-- total_of_sales Report -->
<script>
const ctx5 = document.getElementById('myChart5').getContext('2d');
const myChart5 = new Chart(ctx5, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($month5) ?>,
        datasets: [{
            label: 'Sales per Month' ,
            data: <?php echo json_encode($amount5) ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

<!-- Customers Report -->
<script>
const ctx4 = document.getElementById('myChart4').getContext('2d');
const myChart4 = new Chart(ctx4, {
    type: 'bar',
    data: {
        labels: <?= json_encode($month4) ?>,
        datasets: [{
            label: 'Total of customers per month',
            data: <?php echo json_encode($customer4) ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

<!-- Average -->
<script>
const ctx6 = document.getElementById('myChart6').getContext('2d');
const myChart6 = new Chart(ctx6, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($monthname6) ?>,
        datasets: [{
            label: 'Average purchase per customer',
            data: <?php echo json_encode($avg6) ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
</body>
</html>