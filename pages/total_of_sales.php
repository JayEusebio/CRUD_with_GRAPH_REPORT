
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
        SUM(total_sales) as amount
    FROM sales
    GROUP BY MONTH(sales_date)
  ");

  foreach($result as $data)
  {
    $month[] = $data['monthname'];
    $amount[] = $data['amount'];

  }
//   var_dump($month);
//   echo '<pre>';
//   print_r( $amount);
//   echo '/pre>';
?>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div style="w-auto center">
                        <canvas id="myChart"></canvas>
                        <?php 
                            $total = 0;
                            $result = $query->query("
                            SELECT SUM(total_sales) as sumofsales FROM sales
                            ");
                            foreach ($result as $value) {
                                $total = implode(" ",$value);
                            }

                        ?>
                        <label class="right">Total of Sales from <?php echo implode(", ",$month) ?>: <?php echo $total ?> </label>
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
const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($month) ?>,
        datasets: [{
            label: 'Sales per Month' ,
            data: <?php echo json_encode($amount) ?>,
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