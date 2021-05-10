<?php

// dodaj osobinu artiklu
if($_SERVER["REQUEST_METHOD"] == "POST" AND isset($_GET["newProperty"]) ) {

    $user_id = $_SESSION['user_id'];

    $date = date('Y-m-d h:i:s');
    $article_id = htmlspecialchars($_POST['article_id']);
    $property_item_id = htmlspecialchars($_POST['property_item_id']);
    if(isset($_POST['min'])) {
        $min = trim(htmlspecialchars($_POST['min']));
    } else {
        $min = 0;
    }
  
    if(isset($_POST['max'])) {
        $max = trim(htmlspecialchars($_POST['max']));
    } else {
        $max = 0;
    }

    // echo "$user_id-$date-$article_id-$property_item_id-$min-$max";
  
    $db = new Database();

    $db->connection->query("INSERT INTO article_property (article_id, property_id, min, max) "
                   . "VALUES ('$article_id', '$property_item_id', '$min', '$max')") or die(mysqli_error($db->connection));

    die('<script>location.href = "?view&article_id='.$article_id.'" </script>');
}


// dodaj materijal u sastavnicu
if($_SERVER["REQUEST_METHOD"] == "POST" AND isset($_GET["newMaterial"]) ) { 

    echo 'dodaj materijal u sastavnoicu';
    $user_id = $_SESSION['user_id'];

    $date = date('Y-m-d h:i:s');
    $article_id = htmlspecialchars($_POST['article_id']);
    $material_item_id = htmlspecialchars($_POST['material_item_id']);
    $function = htmlspecialchars($_POST['function']);

    $db = new Database();

    $db->connection->query("INSERT INTO article_material (article_id, material_id, function) "
                                           . "VALUES ('$article_id', '$material_item_id', '$function' )") or die(mysqli_error($db->connection));

    die('<script>location.href = "?view&article_id='.$article_id.'" </script>');

}
