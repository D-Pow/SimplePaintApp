<?php
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
        $query = "INSERT INTO users (username, password) VALUES (:user, :pass);";
        $statement = $db->prepare($query);
        $statement->bindValue(':user', $username);
        $statement->bindValue(':pass', $hashedPassword);
        $statement->execute();
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
                ?>

<form id="drawingForm" action="../drawing.html" method="post">
    <input type=hidden name=username value=<?php echo $username ?>>
    <input type=hidden name=password value=<?php echo $password ?>>
</form>
<script type="text/javascript">document.getElementById("drawingForm").submit();</script>

                <?php
            }
        } else {
            //no results
            rejectLogin();
            return;
        }
    }

    function rejectLogin() {
        ?>
            <form id='reject' action='../index.php' method='post'>
                <input type='hidden' name='failed' value='1'>
            </form>
            <script>document.getElementById('reject').submit();</script>
        <?php
    }

?>