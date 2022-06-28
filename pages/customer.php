
<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Document</title>
</head>
<body>

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
    $month1[] = $data['monthname'];
    $customer[] = $data['customers'];

  }
//   var_dump($month);
//   echo '<pre>';
//   print_r( $amount);
//   echo '/pre>';
?>
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div style="w-auto center">
                        <canvas id="myChart1"></canvas>
                        <?php 
                            $total = 0;
                            $result = $query->query("
                            SELECT SUM(customers) as sumCustomer FROM sales
                            ");
                            foreach ($result as $value) {
                                $total =implode(" ",$value);
                            }

                        ?>
                        <label class="right">Total of Customers from <?php echo implode(", ",$month1) ?>: <?php echo $total ?> </label>
                    </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
<script>
const ctx1 = document.getElementById('myChart1').getContext('2d');
const myChart1 = new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: <?= json_encode($month1) ?>,
        datasets: [{
            label: 'Total of customers per month',
            data: <?php echo json_encode($customer) ?>,
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