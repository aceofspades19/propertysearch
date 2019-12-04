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
    } 
    elseif(isset($_GET['Edit'])){
        edit($_GET['Edit']);
    } else {
        show(); 
    }

    if(isset($_POST['Submit'])){
        edit_submit();
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

  function edit($id){
    $pdo = new PDO('mysql:host=localhost;dbname=properties', "root", "toor");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($id != "New"){
        $stmt = $pdo->prepare("SELECT *  FROM property WHERE Id = ?");
        $stmt->execute(array($id));
        $prop = $stmt->fetch();
    } 
    ?>
    <div class="container">
        <form method="POST" action="?Submit=<?php if(isset($prop->id)){ echo $prop->id; } else { echo "New"; } ?>" enctype='multipart/form-data'>
            <div class="form-group">
                <label for="county">County</label>
                <input type="text" class="form-control" id="county" name="county" placeholder="Enter County" value="<?php if(isset($prop->county)){ echo $prop->county; } ?>">
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" class="form-control" id="country" name="country" placeholder="Enter Country" value="<?php if(isset($prop->country)){ echo $prop->country; } ?>">
            </div>
            <div class="form-group">
                <label for="town">Town</label>
                <input type="text" class="form-control" id="town" name="town" placeholder="Enter Town" value="<?php if(isset($prop->town)){ echo $prop->town; } ?>">
            </div>
            <div class="form-group">
                <label for="Description">Description</label>
                <textarea id="Description" name="description" class="form-control"><?php if(isset($prop->description)){ echo $prop->description; } ?></textarea>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address" value="<?php if(isset($prop->displayable_address)){ echo $prop->displayable_address; } ?>">
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" class="form-control" id="price" name="price" placeholder="Enter Price" value="<?php if(isset($prop->price)){ echo $prop->price; } ?>">
            </div>
            <div class="form-group">
                <label for="numbedrooms">Number of Bedrooms</label>
                <select class="form-control" id="numbedrooms" name="numbedrooms">
                <?php 
                    for($i=0;$i<10;$i++){
                        echo "<option value='" . $i . "'>" . $i . "</option>";
                    }
                ?>
                </select>
            </div>
            <div class="form-group">
                <label for="numbathrooms">Number of Bathrooms</label>
                <select class="form-control" id="numbathrooms" name="numbathrooms">
                <?php 
                    for($i=0;$i<10;$i++){
                        echo "<option value='" . $i . "'>" . $i . "</option>";
                    }
                ?>
                </select>
            </div>
            <div class="form-group">
                <label for="rent" class="form-check-label">For Rent</label>
                <input type="radio" class="form-check-input" id="rent" name="rent" value="y">
            </div>
            <div class="form-group">
                <label for="sale" class="form-check-label">For Sale</label>
                <input type="radio" class="form-check-input" id="sale" name="sale" value="y">
            </div>
            <div class="form-group">
            <?php if(isset($prop->Thumbnail_URL)){ echo "<img src='" . $prop->Thumbnail_URL . "'>"; } ?>
                <label for="imageupload">Image Upload</label>
                <input type="file" class="form-control-file" id="imageupload" name="image">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <?php 
  }

  function edit_submit(){
    $id = $_POST['Submit'];
    $pdo = new PDO('mysql:host=localhost;dbname=properties', "root", "toor");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($id != "New"){
        $stmt= $pdo->prepare("UPDATE property SET  County=?, Country=?, Town=?, Description=?, Full_Details_URL=?, Displayable_Address=?, Image_URL=?, Thumbnail_URL=?, Numbedrooms=?, Numbathrooms=?, Price=?, ForSale=?, ForRent=? WHERE Id=?");
        $stmt->execute(array($_POST['county'], $_POST['country'], $_POST['town'], $_POST['description'], 'test', $_POST['address'], $prop->image_full, $prop->image_thumbnail, $_POST['numbedrooms'], $_POST['numbathrooms'], $_POST['price'], $_POST['sale'], $_POST['rent'], $id));
        echo "<div class='alert alert-success'>Property saved!</div>";
    } else {

        echo "<div class='alert alert-success'>New property added!</div>";
    }

   
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