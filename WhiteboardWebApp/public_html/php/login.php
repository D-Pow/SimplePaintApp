<?php
    /**
     * Note: PDO prepared statements are relatively
     * safe from SQL injection, but we still need many
     * other safety checks before this site is safe.
     */
    $db = new PDO("sqlite:../whiteboards.db");
    $createNewUser = intval($_POST['createNew']);
    $username = $_POST["username"];
    $password = $_POST["password"];
    if ($username == "" || $password == "") {
        return;
    }
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
        $statement->execute();
        acceptLogin($username);
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

    function acceptLogin($username) {
        ?>
            <form id="drawingForm" action="../drawing.html" method="get">
                <input type=hidden name=username value=<?php echo $username ?> >
            </form>
            <script type="text/javascript">document.getElementById("drawingForm").submit();</script>
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