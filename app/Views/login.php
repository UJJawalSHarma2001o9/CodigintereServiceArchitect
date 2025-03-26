<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <center>
        <h2>Login</h2>
        <form id="loginForm">
            <input type="text" id="email" name="email" placeholder="Email" required><br><br>
            <input type="text" id="password" name="password" placeholder="Password" required><br><br>
            <button type="submit">Login</button>
        </form>
        <p id="error-message" style="color: red;"></p>
    </center>


    <script>
        $(document).ready(function () {
            $("#loginForm").submit(function (e) {
                e.preventDefault();

                let email = $("#email").val();
                let password = $("#password").val();
                console.log(`Email: ${email}, Password: ${password}`);

                $.ajax({
                    url: "http://localhost/service-architect/api/user/login", // API URL
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({ email: email, password: password }),
                    success: function (response) {
                        if (response.success === true && response.statusCode === 200) {
                            console.log("Login successful:", response);

                            // Store API key & user details in localStorage
                            localStorage.setItem("apiKey", response.user.apiKey);
                            localStorage.setItem("userData", JSON.stringify(response.user));

                            // Redirect to dashboard
                            window.location.href = "http://localhost/service-architect/dashboard";
                        } else {
                            $("#error-message").text("Unexpected response from the server.");
                        }
                    },
                    error: function (xhr) {
                        let response = xhr.responseJSON;

                        if (response && response.success === false) {
                            console.error("Login failed:", response);
                            let errorMsg = response.message || "Invalid credentials!";

                            if (response.errors && response.errors.password) {
                                errorMsg = response.errors.password;
                            }

                            $("#error-message").text(errorMsg);
                        } else {
                            $("#error-message").text("Server error! Please try again later.");
                        }
                    }
                });
            });
        });
    </script>


</body>

</html>