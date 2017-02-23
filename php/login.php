<?php
    /**
     * Note: PDO prepared statements are relatively
     * safe from SQL injection, but we still need many
     * other safety checks on user input.
     */
    if (isset($_POST['createNew'])) {
        $createNewUser = intval($_POST['createNew']);
    } else {
        $createNewUser = 0;
    }
    if ((!isset($_POST['username'])) || (!isset($_POST['password']))) {
        reply("no values");
    }
    $username = $_POST["username"];
    $password = $_POST["password"];
    if ($username == "" || $password == "") {
        reply("no values");
    }
    try {
        $db = new PDO("sqlite:../sketches.db");
        if ($createNewUser===1) {
            //create new user
            $hashedPassword = hash("sha256", $password);
            $query = "SELECT username FROM users;";
            $statement = $db->prepare($query);
            $statement->execute();
            $results = $statement->fetchAll();
            if ($results) {
                foreach ($results as $row) {
                    if ($row['username'] == $username) {
                        reply("user exists");
                    }
                }
            }
            $query = "INSERT INTO users (username, password) VALUES (:user, :pass);";
            $statement = $db->prepare($query);
            $statement->bindValue(':user', $username);
            $statement->bindValue(':pass', $hashedPassword);
            $success = $statement->execute();
            if ($success) {
                acceptLogin($username);
                reply("accept login");
            } else {
                reply("create failed");
            }
        } else {
            //load user from database
            $query = "SELECT password FROM users WHERE username=:user;";
            $statement = $db->prepare($query);
            $statement->bindValue(':user', $username);
            $statement->execute();
            $results = $statement->fetchAll();
            if ($results) {
                $hashedPassword = hash("sha256", $password);
                $row = $results[0];
                $correctPass = $row['password'];
                if (!($correctPass == $hashedPassword)) {
                    //reject request
                    reply("wrong password");
                } else {
                    acceptLogin($username);
                    reply("accept login");
                }
            } else {
                //no users with that username
                reply("no usernames");
            }
        }
    } finally {
        unset($db);
    }

    function reply($message) {
        echo $message;
        exit();
    }

    function acceptLogin($username) {
        //Set username in cookie so JavaScript can use it
        //setcookie(name, value, expireDate, path)
        setcookie('username', $username, 0, '/');
        setcookie('sketchid', 1, 0, '/');
    }

?>