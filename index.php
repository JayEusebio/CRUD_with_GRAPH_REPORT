<?php
    include 'includes/connection.php';
    include 'includes/controller.php';
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
    <title>CRUD</title>
</head>
<body>
<div class="container p-3 mb-5">
    <?php 
        if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?=$_SESSION['msg_type'] ?>">
    <?php 
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    ?>
    </div>
    <?php endif ?>
    <form action="index.php" method="POST">
        <input type="hidden" name="id" value="<?=$id ?>">
    <div class="input-group input-group-sm mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-md">Date</span>
        </div>
        <input type="date" value="<?= $sales_date ?>" name="sales_date" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
    </div>

    <div class="input-group input-group-sm mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-md">Total Sales</span>
        </div>
        <input type="text" value="<?= $total_sales ?>" name="totalofsales" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
    </div>

    <div class="input-group input-group-sm mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-md">Numbers of Customers</span>
        </div>
        <input type="text" value="<?= $numofcustomer ?>" name="numberofcustomer" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
    </div>
    <?php 
        if ($update == true):
    ?>
        <button type="submit" class="btn btn-outline-primary" name="update">Update</button>
    <?php else: ?>
        <button type="submit" class="btn btn-outline-primary" name="submit">Submit</button>
    <?php endif; ?>
    </form>
</div>
<div class="container">
   <div class="container m-4"> 

        <a href="pages/monthly_report.php" class="btn btn-outline-secondary btn-sm">
            Monthly Report!
        </a>       
        <a href="pages/daily_sales.php" class="btn btn-outline-secondary btn-sm text-center">
            Daily Sales Report
        </a>
        <a href="pages/daily_customers.php" class="btn btn-outline-secondary btn-sm text-center">
            Daily Customer Report
        </a>
        <a href="pages/daily_average.php" class="btn btn-outline-secondary btn-sm text-center">
            Daily Average Report
        </a>
    </div>
    <table class="table">
        <thead class="table-dark text-center">
            <tr>
            <th >Date</th>
            <th >Total Sales</th>
            <th >Numbers of Customers</th>
            <th >Actions</th>
            </tr>
        </thead>
        <?php  
              
            $result = $query->query("SELECT * FROM sales ORDER BY sales_date DESC") or die (mysqli_error($query));
                
            foreach($result as $sales) { 
               $converted = strtotime($sales['sales_date']);
               $newconverted = date("M d, Y", $converted);
                ?>
        <tbody class="text-center">
            <tr>
            <td><?=  $newconverted ?></td>
            <td>PHP <?= number_format($sales['total_sales'],2)  ?></td>
            <td><?= $sales['customers'] ?></td>
            <td><a href="index.php?edit=<?= $sales['id'] ?>" class="btn btn-outline-success">Edit</a> <a href="includes/controller.php?delete=<?= $sales['id'] ?>" class="btn btn-outline-danger">Delete</a></td>
            </tr>
        </tbody>
        <?php  }?>
        </table>
    </tbody>
    </table>
    <?php require_once 'pages/daily_sales.php' ?>


</div>
</body>
</html>