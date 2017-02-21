<?php
    $username = $_COOKIE['username'] != '' ? $_COOKIE['username'] : '';
    if ($username == '') {
        return;
    }
    $sketchid = $_POST['sketchid'];
    $image = $_POST['sketch'];
    try {
        $db = new PDO("sqlite:../sketches.db");
        //Check if sketchid already exists
        $query = "SELECT username, sketchid FROM sketches WHERE username=:user;";
        $statement = $db->prepare($query);
        $statement->bindValue(":user", $username);
        $success = $statement->execute();
        if ($success) {  //if user has made sketches before
            $sIDs = array();
            $results = $statement->fetchAll();
            foreach ($results as $row) {
                $sIDs[] = $row['sketchid'];
            }
        }
        if ((!$success) || (!in_array($sketchid, $sIDs))) {
            $insertedCorrectly = insertSketch($db, $username, $sketchid, $image);
            if ($insertedCorrectly) {
                echo "Sketch saved!";
            } else {
                echo "There was a problem saving your sketch.";
            }
            return;
        } else {
            echo "validate";  //ask JavaScript to verify that user should overwrite data
            return;
        }
    } finally {
        unset($db);
    }

    function insertSketch($db, $username, $sketchid, $image) {
        //If sketch doesn't exist yet
        $query = "INSERT INTO sketches VALUES (:user, :sid, :image);";
        $statement = $db->prepare($query);
        $statement->bindValue(":user", $username);
        $statement->bindValue(":sid", $sketchid);
        $statement->bindValue(':image', $image);
        $success = $statement->execute();
        return true;
    }
?>