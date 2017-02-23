<!DOCTYPE html>
<html>
<head>
    <!--Force browser to pull page from server-->
    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="pragma" content="no-cache">
    <link rel="stylesheet" href="css/drawingstyle.css" />
    <link rel="stylesheet" href="css/indexstyle.css" />
    <title>Whiteboard Login</title>
</head>
<body>
<div id='container'>
    <h2 style="font-family: cursive;">Whiteboard</h2>
        <table>
            <tr>
                <td><b>Username:</b></td>
                <td><input type="text" id="user" name="username"></td>
            </tr>
            <tr>
                <td><b>Password:</b></td>
                <td><input type="password" id="pass" name="password"></td>
            </tr>
            <tr>
                <td><input type="button" value="Login" onclick="login()"></td>
                <td><input type="button" value="Create Account" onclick="createAccount()"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <p id='feedback'></p>
                </td>
            </tr>
        </table>
    </div>
    <br />
    <br />
    <script>
        function displayFeedback(message) {
            document.getElementById("feedback").innerHTML = message;
        }

        function createAccount() {
            var username = document.getElementById("user").value;
            var password = document.getElementById("pass").value;

            $.ajax({
                method: 'POST',
                url: 'php/login.php',
                data: {
                    createNew: 1,
                    username:  username,
                    password:  password
                },
                success: function(result) {
                    //Username or password were blank
                    if (result == 'no values') {
                        displayFeedback("Please enter both username and password");
                    //Username already taken
                    } else if (result == 'user exists') {
                        displayFeedback('That username is not available');
                    //If there were a problem creating the account
                    } else if (result == "create failed") {
                        displayFeedback("There was a problem creating your account");
                    //Correct login; redirect
                    } else {
                        //Make sure cookie exists first
                        if (document.cookie) {
                            window.location.href = "drawing.html";
                        }
                    }
                }
            });
        }

        function login(username, password) {
            var username = document.getElementById("user").value;
            var password = document.getElementById("pass").value;

            $.ajax({
                method: 'POST',
                url: 'php/login.php',
                data: {
                    username:  username,
                    password:  password
                },
                success: function(result) {
                    //Username or password were blank
                    if (result == 'no values') {
                        displayFeedback("Please enter both username and password");
                    //If right username, wrong password
                    } else if (result == "wrong password") {
                        displayFeedback("Incorrect username-password combination");
                    //If no accounts exist with that username
                    } else if (result == "no usernames") {
                        displayFeedback("That username doesn't exist");
                    //Correct login; redirect
                    } else {
                        //Make sure cookie exists first
                        if (document.cookie) {
                            window.location.href = "drawing.html";
                        }
                    }
                }
            });
        }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</body>
</html>