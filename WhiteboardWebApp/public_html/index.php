<!DOCTYPE html>
<html>
<head>
    <!--Force browser to pull page from server-->
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="pragma" content="no-cache">
    <link rel="stylesheet" href="css/style.css" />
    <title>Whiteboard Login</title>
    <style type="text/css">
        html {
            width: 100%;
            height: 100%;
        }
        body {
            display: block;
            background-color: rgb(134, 234, 204);
            height: 15%;
            width: 20%;
            padding: 100px 0;
            margin: auto;
        }
    </style>
</head>
<body>
    <h2 style="font-family: cursive;">Whiteboard</h2>
    <form id="loginForm" action="php/login.php" method="post">
        <table>
            <tr>
                <td><b>Username:</b></td>
                <td><input type="text" id="user" name="username"></td>
            </tr>
            <tr>
                <td><b>Password:</b></td>
                <td><input type="text" id="pass" name="password"></td>
            </tr>
            <tr>
                <td><input type="submit" value="Login"</td>
                <td><input type="button" value="Create Account" onclick="createLogin()"</td>
            </tr>
        </table>
        <input id="createNew" name="createNew" type="hidden" name="createUser" value="0">
    </form>
    <br />
    <br />
    <script>
        var form = document.getElementById("loginForm");
        
        function createLogin() {
            var input = document.getElementById("createNew");
            input.value = 1;
            form.submit();
        }
    </script>
</body>
</html>

<?php
    if(isset($_POST['failed'])) {
        if ($_POST['failed'] == "1") {
            echo "Login failed.";
        }
        else if ($_POST['failed'] == "2") {
            echo "Create account failed: user already exists.";
        }
    }
?>