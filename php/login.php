<?php
    /**
     * Note: PDO prepared statements are relatively
     * safe from SQL injection, but we still need many
     * other safety checks on user input.
     */
    $createNewUser = intval($_POST['createNew']);
    $username = $_POST["username"];
    $password = $_POST["password"];
    if ($username == "" || $password == "") {
        return;
    }
    try {
        $db = new PDO("sqlite:../sketches.db");
        if ($createNewUser) {
            //create new user
            $hashedPassword = hash("sha256", $password);
            $query = "SELECT username FROM users;";
            $statement = $db->prepare($query);
            $statement->execute();
            $results = $statement->fetchAll();
            if ($results) {
                foreach ($results as $row) {
                    if ($row['username'] == $username) {
                        rejectCreate();
                        return;
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
            } else {
                rejectCreate();
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
                    rejectLogin();
                    return;
                } else {
                    acceptLogin($username);
                }
            } else {
                //no results
                rejectLogin();
                return;
            }
        }
    } finally {
        unset($db);
    }

    function acceptLogin($username) {
        //Set username in cookie so JavaScript can use it
        //setcookie(name, value, expireDate, path)
        setcookie('username', $username, 0, '/');
        setcookie('sketchid', 1, 0, '/');
        ?>
            <form id="drawingForm" action="../drawing.html" method="post">
            </form>
            <script type="text/javascript">
                //Submit form
                document.getElementById("drawingForm").submit();
            </script>
        <?php
    }

    function rejectLogin() {
        ?>
            <form id='reject' action='../index.php' method='post'>
                <input type='hidden' name='failed' value='1'>
            </form>
            <script>document.getElementById('reject').submit();</script>
        <?php
    }

    function rejectCreate() {
        ?>
            <form id='reject' action='../index.php' method='post'>
                <input type='hidden' name='failed' value='2'>
            </form>
            <script>document.getElementById('reject').submit();</script>
        <?php
    }

?>