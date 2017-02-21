<?php
    $username = $_COOKIE['username'] != '' ? $_COOKIE['username'] : '';
    $sketchid = $_COOKIE['sketchid'] != '' ? $_COOKIE['sketchid'] : '';
    if ($username == '' || $sketchid == '') {
        echo "bad cookie";
        return;
    }
    try {
        $db = new PDO("sqlite:../sketches.db");
        //Check if sketchid already exists
        $sketchAndUserExists = sketchExists($db, $username, $sketchid);
        //if neither the user nor sketchid exist, then this is the first
        //username-sketchid combo inserted into the database
        if (!$sketchAndUserExists) {
            echo "not present";
            return;
        } else {
            $result = loadSketch($db, $username, $sketchid);
            if ($result === false)  {
                echo "problem loading";
            } else {
                echo $result;
            }
            return;
        }
    } finally {
        unset($db);
    }

    function loadSketch($db, $username, $sketchid) {
        //If sketch doesn't exist yet
        $query = "SELECT sketch FROM sketches WHERE username=:user AND sketchid=:sid;";
        $statement = $db->prepare($query);
        $statement->bindValue(":user", $username);
        $statement->bindValue(":sid", $sketchid);
        $success = $statement->execute();
        if ($success) {
            $results = $statement->fetchAll();
            $row = $results[0];
            return $row['sketch'];
        } else {
            return false;
        }
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