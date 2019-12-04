<!DOCTYPE html>
<html lang="en">
  <?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
  ?>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Property Search</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <h1>Property Admin</h1>
    <?php 
    if(isset($_GET['Delete'])){
        delete($_GET['Delete']);
    } else {
        show(); 
    }

    function show(){
        ?>
        <a href="?Edit=New" class='btn btn-default'>Add New Property</a><br><br><br>
        <table class="table">
        <thead>
            <tr>
            <th scope="col">Image</th>
            <th scope="col">Address</th>
            <th scope="col">Town</th>
            <th scope="col">State</th>
            <th scope="col">Country</th>
            <th scope="col">Number of bedrooms</th>
            <th scope="col">Number of bathrooms</th>
            <th scope="col">Price</th>
            <th scope="col"></th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        <?php 
        try {
        $pdo = new PDO('mysql:host=localhost;dbname=properties', "root", "toor");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $data = $pdo->query("SELECT *  FROM property")->fetchAll();     
        foreach ($data as $row) {
            echo "<tr><td><img src='" . $row["Thumbnail_URL"] . "'></td>";
            echo "<td>" . $row['Displayable_Address'] . "</td>";
            echo "<td>" . $row['Town'] . "</td>";
            echo "<td>" . $row['County'] . "</td>";
            echo "<td>" . $row['Country'] . "</td>";
            echo "<td>" . $row['Numbedrooms'] . "</td>";
            echo "<td>" . $row['Numbathrooms'] . "</td>";
            echo "<td>" . $row['Price'] . "</td>";
            echo "<td><a href='?Edit=" . $row["id"] . "' class='btn btn-default'>Edit</a></td>";
            echo "<td><a href='?Delete=" . $row["id"] . "' class='btn btn-default'>Delete</a></td>";
            echo "</tr>";
        }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

    ?>
     </tbody>
    </table>
    <?php 
  }

  function delete($id){
    $pdo = new PDO('mysql:host=localhost;dbname=properties', "root", "toor");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("DELETE FROM property WHERE id = :id");
    $stmt->bindParam(':id', $id);   
    $stmt->execute();
    echo "<div class='alert alert-success'>Property deleted!</div>";
    show(); 
  }
    
    ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
      $(document).ready( function () {
        $('table').DataTable();
      } );
    </script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>