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

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <h1>Property Search</h1>
    <?php 
      $baseurl = "http://trialapi.craig.mtcdevserver.com/";
      $properties_url = "api/properties";
      $api_key = "3NLTTNlXsi6rBWl7nYGluOdkl2htFHug";
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $baseurl . $properties_url . "?api_key=" . $api_key);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result = json_decode(curl_exec($ch));
      
      
      
     foreach($result->data as $prop){
      insert_prop($prop);
     }
      curl_close($ch);

      function insert_prop($prop){
        try {
          $pdo = new PDO('mysql:host=localhost;dbname=properties', "root", "toor");
          $stmt = $pdo->prepare("SELECT *  FROM property WHERE Id = ?");
          $stmt->execute(array($prop->uuid));
          $exists = $stmt->rowCount();
          if($prop->type == "sale"){
            $sale = "y";
            $rent = "n";
          } else {
            $sale = "n";
            $rent = "y";
          }
          if($exists > 0){

          } else {
            $stmt= $pdo->prepare("INSERT INTO property ( Id, County, Country, Town, Description, Full_Details_URL, Displayable_Address, Image_URL, Thumbnail_URL, Latitude, Longitude, Numbedrooms, Numbathrooms, Price, PropertyType, ForSale, ForRent), VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?");
            $stmt->execute(array($prop->uuid, $prop->county, $prop->country, $prop->town, $prop->description, "test", $prop->address, $prop->image_full, $prop->image_thumbnail, $prop->latitude, $prop->longitude, $prop->num_bedrooms, $prop->num_bathrooms, $prop->price, $prop->property_type_id, $sale, $rent));
          }

          
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
      }
    ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>