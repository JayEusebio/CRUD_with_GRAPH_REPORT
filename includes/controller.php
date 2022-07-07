<?php

   session_start();
   include 'connection.php';

   $update = false;

   $id = 0;
   $sales_date = '';
   $total_sales = '';
   $numofcustomer = '';

   if (isset($_POST['submit'])) {

      $sales_date = $_POST['sales_date'];
      $total_sales = floatval(preg_replace('/[^\d.]/', '', $_POST['totalofsales']));
      $numofcustomer = floatval(preg_replace('/[^\d.]/', '', $_POST['numberofcustomer']));

      var_dump($total_sales);

      $query->query("INSERT INTO sales (sales_date, total_sales, customers) VALUES ('$sales_date', '$total_sales', '$numofcustomer')") or die($query->error());

      $_SESSION['message'] = "Record has been saved!";
      $_SESSION['msg_type'] = "success";

      $sales_date = '';
      $total_sales = '';
      $numofcustomer = '';
   }

   if (isset($_GET['delete'])) {

      $id = $_GET['delete'];

      $query->query("DELETE FROM sales WHERE id=$id") or die($query->error());

      $_SESSION['message'] = "Record has been deleted!";
      $_SESSION['msg_type'] = "danger";

      header("location: ../index.php");
   }

   if (isset($_GET['edit'])) {
      
      $id = $_GET['edit'];
      $update = true;

      $result = $query->query("SELECT * FROM sales WHERE id=$id") or die($query->error());
   
         $row = $result->fetch_array();

         $sales_date = $row['sales_date'];
         $total_sales = $row['total_sales'];
         $numofcustomer = $row['customers'];
         
   }

   if (isset($_POST['update'])) {

      $id = $_POST['id'];
      $sales_date = $_POST['sales_date'];
      $total_sales = $_POST['totalofsales'];
      $numofcustomer = $_POST['numberofcustomer'];

      $query->query("UPDATE sales SET sales_date='$sales_date', total_sales='$total_sales', customers='$numofcustomer' WHERE id=$id ") or die($query->error());

      $_SESSION['message'] = "Record has been updated!";
      $_SESSION['msg_type'] = "warning";

      $sales_date = '';
      $total_sales = '';
      $numofcustomer = '';
   }