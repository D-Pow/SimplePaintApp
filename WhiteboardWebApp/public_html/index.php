<!DOCTYPE html>
<html>
<head>
  <title>Whiteboard Login</title>
</head>
<body>
    <h2>Whiteboard</h2>
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
        echo "Login failed";
    }
?>