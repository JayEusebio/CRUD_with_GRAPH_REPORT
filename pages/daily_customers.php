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
    SELECT COUNT(*), DAY(sales_date) AS Days, MONTHNAME(sales_date), YEAR(sales_date), sales_date, total_sales, customers FROM sales WHERE MONTHNAME(sales_date) = 'June'
    GROUP BY YEAR(sales_date), MONTH(sales_date), DAY(sales_date)
    ");

    foreach($result as $data)
    {
        $customerData[] = $data['customers'];
        $custMonth[] = $data['sales_date'];

        $custDate = json_encode($custMonth);
        $dailyCustomer = json_encode($customerData);
        
    }
?>
<div class="container mt-5 pt-5">
    <h1 class="text-center">Daily Customer Report</h1>
    <div class="w-auto">
        <canvas id="myChart20"></canvas>
        <?php 
            $total20 = 0;
            $result = $query->query("
                SELECT SUM(customers) as sumofcustomer FROM sales
            ");
                foreach ($result as $value) {
                    $total20 = implode(" ",$value);
                }
        ?>
        <div class="mx-2 my-2 d-flex">
            <div class="mx-2 my-2 "> 
                <input class="mx-2" type="date" name="" id="custStardtate" value="2022-06-01"> <span>to</span> 
                <input class="mx-2" type="date" name="" id="custEnddate" value="2022-06-02">
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
                <label>Total Customers: <?= number_format($total20)  ?></label>
            </div>
        </div>
    </div>

</div>
<script>
    const custDatedata = <?= $custDate ?>;
    const numCustomer = <?= $dailyCustomer ?>;
    const custconvertedDates = custDatedata.map(date => new Date(date).toLocaleString('en-US', {
        timeZone: 'Asia/Manila',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    }));

    const data = {
        labels: custDatedata,
        datasets: [{
        label: 'Daily Customers',
        data: numCustomer,
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

    const myChart20 = new Chart(
        document.getElementById('myChart20'),
        config
    );

    function filterDate(){
        
        const custStart = new Date(document.getElementById('custStardtate').value).toLocaleString('en-US', {
            timeZone: 'Asia/Manila',
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
        });
        console.log(custStart);


        const custEnd = new Date(document.getElementById('custEnddate').value).toLocaleString('en-US', {
            timeZone: 'Asia/Manila',
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
        });

        const custFilteringdates = custconvertedDates.filter(date => date >= custStart && date <= custEnd)

        myChart20.config.data.labels = custFilteringdates;


        const custstartArray = custconvertedDates.indexOf(custFilteringdates[0])
        const custendArray = custconvertedDates.indexOf(custFilteringdates[custFilteringdates.length - 1])
        const custnewData = [... numCustomer];

        custnewData.splice(custendArray + 1, custFilteringdates.length);
        custnewData.splice(0, custstartArray);

        myChart20.config.data.datasets[0].data = custnewData;

        myChart20.update();
    }

</script>

</body>
</html>