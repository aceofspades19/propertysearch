<!DOCTYPE html>
<html lang="en">
  <?php
    /* ini_set('display_errors', 1);
    error_reporting(E_ALL);
    ini_set("display_errors", 1); */
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
    //routing for admin
    if(isset($_GET['Delete'])){
        delete($_GET['Delete']);
    } 
    elseif(isset($_GET['Edit'])){
        edit($_GET['Edit']);
    }
    elseif(isset($_GET['Submit'])){
        edit_submit();
        show(); 
    }
    else {
        show(); 
    }


    //displays the list of properties
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
            echo "<tr><td><a href='". $row["Image_URL"] . "'><img src='" . $row["Thumbnail_URL"] . "'></a></td>";
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

  //deletes a property
  function delete($id){
    $pdo = new PDO('mysql:host=localhost;dbname=properties', "root", "toor");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("DELETE FROM property WHERE id = :id");
    $stmt->bindParam(':id', $id);   
    $stmt->execute();
    echo "<div class='alert alert-success'>Property deleted!</div>";
    show(); 
  }

  //provides form to add/edit a property
  function edit($id){
    $pdo = new PDO('mysql:host=localhost;dbname=properties', "root", "toor");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($id != "New"){
        $stmt = $pdo->prepare("SELECT *  FROM property WHERE Id = ?");
        $stmt->execute(array($id));
        $prop = $stmt->fetchObject();
    } 
    ?>
    <div class="container">
        <form method="POST" action="?Submit=<?php if(isset($prop->id)){ echo $prop->id; } else { echo "New"; } ?>" enctype='multipart/form-data'>
            <div class="form-group">
                <label for="county">County</label>
                <input type="text" class="form-control" id="county" name="county" placeholder="Enter County" value="<?php if(isset($prop->County)){ echo $prop->County; } ?>">
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" class="form-control" id="country" name="country" placeholder="Enter Country" value="<?php if(isset($prop->Country)){ echo $prop->Country; } ?>">
            </div>
            <div class="form-group">
                <label for="town">Town</label>
                <input type="text" class="form-control" id="town" name="town" placeholder="Enter Town" value="<?php if(isset($prop->Town)){ echo $prop->Town; } ?>">
            </div>
            <div class="form-group">
                <label for="Description">Description</label>
                <textarea id="Description" name="description" class="form-control"><?php if(isset($prop->Description)){ echo $prop->Description; } ?></textarea>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address" value="<?php if(isset($prop->Displayable_Address)){ echo $prop->Displayable_Address; } ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" class="form-control" id="price" name="price" placeholder="Enter Price" value="<?php if(isset($prop->Price)){ echo $prop->Price; } ?>" required>
            </div>
            <div class="form-group">
                <label for="numbedrooms">Number of Bedrooms</label>
                <select class="form-control" id="numbedrooms" name="numbedrooms">
                <?php 
                    for($i=0;$i<10;$i++){
                        if(isset($prop->Numbedrooms) && $prop->Numbedrooms == $i){
                            echo "<option value='" . $i . "' selected='selected'>" . $i . "</option>";
                        } else {
                            echo "<option value='" . $i . "'>" . $i . "</option>";
                        }
                    }
                ?>
                </select>
            </div>
            <div class="form-group">
                <label for="numbathrooms">Number of Bathrooms</label>
                <select class="form-control" id="numbathrooms" name="numbathrooms">
                <?php 
                    for($i=0;$i<10;$i++){
                        if(isset($prop->Numbathrooms) && $prop->Numbathrooms == $i){
                            echo "<option value='" . $i . "' selected='selected'>" . $i . "</option>";
                        } else {
                            echo "<option value='" . $i . "'>" . $i . "</option>";
                        }
                    }
                ?>
                </select>
            </div>
            <div class="form-group">
                <label for="rent" class="form-check-label">For Rent</label>
                <input type="radio" class="form-check-input" id="rent" name="rent" value="y" <?php if(isset($prop->ForSale) && $prop->ForSale=="y"){ echo "checked"; } ?> >
            </div>
            <div class="form-group">
                <label for="sale" class="form-check-label">For Sale</label>
                <input type="radio" class="form-check-input" id="sale" name="sale" value="y" <?php if(isset($prop->ForRent) && $prop->ForRent=="y"){ echo "checked"; } ?>>
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

  //adds or updates the property in the database
  function edit_submit(){
    $id = $_GET['Submit'];
    //upload file if there is any and resize it
    if(isset($_FILES['image']["name"])){
        $name = $_FILES["image"]["name"];
        move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $name);
        $path = "uploads/" . $name;
        resize_image($path, 200, 200, $name);
    } else {
        $path = ""; 
    }
    
    //update or insert into database 
    $pdo = new PDO('mysql:host=localhost;dbname=properties', "root", "toor");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($id != "New"){
        if($name){
            $stmt= $pdo->prepare("UPDATE property SET  County=?, Country=?, Town=?, Description=?, Full_Details_URL=?, Displayable_Address=?, Numbedrooms=?, Numbathrooms=?, Price=?, ForSale=?, ForRent=?, Thumbnail_URL=?, Image_URL=? WHERE Id=?");
            $stmt->execute(array($_POST['county'], $_POST['country'], $_POST['town'], $_POST['description'], 'test', $_POST['address'], $_POST['numbedrooms'], $_POST['numbathrooms'], $_POST['price'], $_POST['sale'], $_POST['rent'], "/propertysearch/uploads/small_" . $name, "/propertysearch/uploads/" . $name, $id));
        } else {
            $stmt= $pdo->prepare("UPDATE property SET  County=?, Country=?, Town=?, Description=?, Full_Details_URL=?, Displayable_Address=?, Numbedrooms=?, Numbathrooms=?, Price=?, ForSale=?, ForRent=? WHERE Id=?");
            $stmt->execute(array($_POST['county'], $_POST['country'], $_POST['town'], $_POST['description'], 'test', $_POST['address'], $_POST['numbedrooms'], $_POST['numbathrooms'], $_POST['price'], $_POST['sale'],  $_POST['rent'], $id));

        }
        echo "<div class='alert alert-success'>Property saved!</div>";
    } else {
        if($name){
            $stmt= $pdo->prepare("INSERT INTO property ( Id, County, Country, Town, Description, Displayable_Address, Numbedrooms, Numbathrooms, Price, ForSale, ForRent, Thumbnail_URL, Image_URL) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute(array(random_bytes(16), $_POST['county'], $_POST['country'], $_POST['town'], $_POST['description'],  $_POST['address'],  $_POST['numbedrooms'], $_POST['numbathrooms'], $_POST['price'], $_POST['sale'], $_POST['rent'], "/propertysearch/uploads/small_" . $name, "/propertysearch/uploads/" . $name,));
        } else {
            $stmt= $pdo->prepare("INSERT INTO property ( Id, County, Country, Town, Description, Displayable_Address, Numbedrooms, Numbathrooms, Price, ForSale, ForRent) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute(array(random_bytes(16), $_POST['county'], $_POST['country'], $_POST['town'], $_POST['description'],  $_POST['address'],  $_POST['numbedrooms'], $_POST['numbathrooms'], $_POST['price'], $_POST['sale'], $_POST['rent']));

        }
        echo "<div class='alert alert-success'>New property added!</div>";
    }

   
  }

  //resizes an image
  function resize_image($file, $w, $h, $name="", $crop=FALSE) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    imagejpeg($dst,"uploads/" . "small_" . $name);
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