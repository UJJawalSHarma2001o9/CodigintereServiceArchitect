<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <center>
        <h2 id="welcome-message">Hello, Login User</h2>
        <p id="api-key">API Key: Not Available</p>
    </center>

    <script>
        // Get stored user data and API key
        let userData = localStorage.getItem("userData");
        let apiKey = localStorage.getItem("apiKey");

        if (userData) {
            let user = JSON.parse(userData);
            document.getElementById("welcome-message").innerHTML = `Hello, ${user.email}`;
        } else {
            document.getElementById("welcome-message").innerHTML = "Hello, Guest";
        }

        if (apiKey) {
            document.getElementById("api-key").innerHTML = `API Key: ${apiKey}`;
        } else {
            document.getElementById("api-key").innerHTML = "API Key: Not Available";
        }
    </script>
</body>
</html>
