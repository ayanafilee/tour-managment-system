<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group 1 - Tour Management Login</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --accent-color: #3498db;
            --success-color: #27ae60;
            --text-light: #ecf0f1;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                        url('https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 420px;
            backdrop-filter: blur(5px);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: var(--primary-color);
            font-size: 24px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--primary-color);
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: var(--accent-color);
            outline: none;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.3);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-login:hover {
            background-color: #2980b9;
        }

        .extra-links {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            font-size: 13px;
        }

        .extra-links a {
            color: var(--accent-color);
            text-decoration: none;
        }

        .extra-links a:hover {
            text-decoration: underline;
        }

        .group-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--success-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <div class="group-badge">GROUP 1 - PROVIDER</div>

    <div class="login-card">
        <div class="login-header">
            <h1>Tour Connect</h1>
            <p>Management & Tourist Portal</p>
        </div>

        <form id="loginForm">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="e.g. user@example.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-login">SIGN IN</button>

            <div class="extra-links">
                <a href="signup.php">Create Account</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // We only send credentials. The server tells us the role.
            const loginData = {
                email: email,
                password: password
            };

            fetch('../backend/auth_login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(loginData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP Error: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    // Success! data.user.role contains the role from the DB
                    // Automatic Routing based on Database Role
                    if (data.user.role === 'guide') {
                        window.location.href = 'add_tour.php'; 
                    } else if (data.user.role === 'tourist') {
                        window.location.href = 'tourist_home.php'; 
                    } else if (data.user.role === 'admin') {
                        window.location.href = 'manage_users.php';
                    } else {
                        alert("Role not recognized. Contact support.");
                    }
                } else {
                    alert("Login Failed: " + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Could not reach backend. Verify XAMPP is active.");
            });
        });
    </script>

</body>
</html>