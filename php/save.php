<?php
    $username = $_COOKIE['username'] != '' ? $_COOKIE['username'] : '';
    $sketchid = $_COOKIE['sketchid'] != '' ? $_COOKIE['sketchid'] : '';
    if ($username == '' || $sketchid == '') {
        echo "bad cookie";
        return;
    }
    $image = $_POST['sketch'];
    try {
        $db = new PDO("sqlite:../sketches.db");
        //Check if sketchid already exists
        $sketchAndUserExists = sketchExists($db, $username, $sketchid);
        //if neither the user nor sketchid exist, then this is the first
        //username-sketchid combo inserted into the database
        if (!$sketchAndUserExists) {
            $insertedCorrectly = insertSketch($db, $username, $sketchid, $image);
            if ($insertedCorrectly) {
                echo "inserted";
            } else {
                echo "not inserted";
            }
            return;
        } else {
            if (isset($_POST['replace'])) {
                $replacedCorrectly = replaceSketch($db, $username, $sketchid, $image);
                if ($replacedCorrectly) {
                    echo 'replaced';
                } else {
                    echo 'failed';
                }
            } else {
                echo "validate";  //ask JavaScript to verify that user should overwrite data
            }
            return;
        }
    } finally {
        unset($db);
    }

    function replaceSketch($db, $username, $sketchid, $image) {
        //Replace the sketch that already exists
        $query = "UPDATE sketches SET sketch=:image WHERE username=:user AND sketchid=:sid;";
        $statement = $db->prepare($query);
        $statement->bindValue(":user", $username);
        $statement->bindValue(":sid", $sketchid);
        $statement->bindValue(':image', $image);
        $success = $statement->execute();
        return $success;
    }

    function insertSketch($db, $username, $sketchid, $image) {
        //If sketch doesn't exist yet
        $query = "INSERT INTO sketches VALUES (:user, :sid, :image);";
        $statement = $db->prepare($query);
        $statement->bindValue(":user", $username);
        $statement->bindValue(":sid", $sketchid);
        $statement->bindValue(':image', $image);
        $success = $statement->execute();
        return $success;
    }

    function sketchExists($db, $username, $sketchid) {
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
        return ($success && in_array($sketchid, $sIDs));
    }
?>