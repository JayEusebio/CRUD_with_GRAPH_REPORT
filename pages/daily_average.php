<?php
    include '../includes/connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Document</title>
</head>
<body>

<?php 
    $result = $query->query("
        SELECT COUNT(*), DAY(sales_date) AS Days, MONTHNAME(sales_date), YEAR(sales_date), sales_date, total_sales, customers,
        SUM(total_sales) as amount, 
        SUM(customers) as total,
        total_sales/customers AS 'Average'
        FROM sales WHERE MONTHNAME(sales_date) = 'June'
        GROUP BY YEAR(sales_date), MONTH(sales_date), DAY(sales_date)
    ");

    foreach($result as $data)
    {
    
        $avgMonth [] = $data['sales_date'];
        $avg [] = $data['Average'];

        $avgDate = json_encode($avgMonth);
        $avgData= json_encode($avg);

  
    }
    
?>
<div class="container mt-5 pt-5">
    <h1 class="text-center">Daily Average Report</h1>
    <div class="w-auto">
        <canvas id="myChart21"></canvas>
        <?php 
            $average = 0;
            $result = $query->query("
                SELECT SUM(customers) as sumCustomer, SUM(total_sales) as sumTotalsales FROM sales
            ");
            foreach($result as $data){
                $custValue = $data['sumCustomer'];
                $salesValue = $data['sumTotalsales'];
                $average = $salesValue / $custValue;

            }
        ?>
        <div class="mx-2 my-2 d-flex">
            <div class="mx-2 my-2 "> 
                <input class="mx-2" type="date" name="" id="avgStardtate" value="2022-06-01"> <span>to</span> 
                <input class="mx-2" type="date" name="" id="avgEnddate" value="2022-06-02">
                <button onclick="filterDate()" class="btn btn-outline-primary"
                    style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                        Filter
                </button>
                <button onclick="resetDate()" class="btn btn-outline-info"
                    style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                        Reset
                </button> 
            </div>
            <div class="text-end mt-2 ms-auto">
                <label>Average Purchase per Customer: <?= number_format($average,2)  ?></label>
            </div>
        </div>
    </div>

</div>
<script>

    const avgDatedata = <?= $avgDate ?>;
    const averageData = <?= $avgData ?>;
    const avgconvertedDates = avgDatedata.map(date => new Date(date).toLocaleString('en-US', {
        timeZone: 'Asia/Manila',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    }));

    const data = {
        labels: avgDatedata,
        datasets: [{
        label: 'Daily Average',
        data: averageData,
        backgroundColor: 'rgb(255, 99, 132)',
        borderColor: 'rgba(255, 99, 132, 1)',
        
        }]
    };

    const config = {
        type: 'bar',
        data,
        options: {
            scales: {

                y: {
                    beginAtZero: true
                }
            }
        }
    };

    const myChart21 = new Chart(
        document.getElementById('myChart21'),
        config
    );

    function filterDate(){
        
        const avgStart = new Date(document.getElementById('avgStardtate').value).toLocaleString('en-US', {
            timeZone: 'Asia/Manila',
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
        });
        console.log(avgStart);


        const avgEnd = new Date(document.getElementById('avgEnddate').value).toLocaleString('en-US', {
            timeZone: 'Asia/Manila',
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
        });

        const avgFilteringdates = avgconvertedDates.filter(date => date >= avgStart && date <= avgEnd)

        myChart21.config.data.labels = avgFilteringdates;


        const avgstartArray = avgconvertedDates.indexOf(avgFilteringdates[0])
        const avgendArray = avgconvertedDates.indexOf(avgFilteringdates[avgFilteringdates.length - 1])
        const avgnewData = [... averageData];

        avgnewData.splice(avgendArray + 1, avgFilteringdates.length);
        avgnewData.splice(0, avgstartArray);

        myChart21.config.data.datasets[0].data = avgnewData;

        myChart21.update();
    }

</script>

</body>
</html>