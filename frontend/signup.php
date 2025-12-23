<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Group 1 - Tour Management</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --accent-color: #3498db;
            --success-color: #2ecc71;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                        url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px 0;
        }

        .signup-card {
            background: rgba(255, 255, 255, 0.98);
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 500px;
        }

        .signup-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .signup-header h2 {
            color: var(--primary-color);
            margin: 0;
            font-size: 26px;
        }

        .signup-header p {
            color: #7f8c8d;
            font-size: 14px;
        }

        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 15px;
            flex: 1;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 14px;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
            height: 80px;
        }

        .btn-signup {
            width: 100%;
            padding: 12px;
            background-color: var(--success-color);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-signup:hover {
            background-color: #27ae60;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .footer-text a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="signup-card">
        <div class="signup-header">
            <h2>Create Account</h2>
            <p>Register as a Tour Guide or Tourist</p>
        </div>

        <form id="signupForm">
            <div class="form-row">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" id="fullname" name="fullname" placeholder="John Doe" required>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select id="role" name="role" required>
                        <option value="guide">Tour Guide</option>
                        <option value="tourist" selected>Tourist</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" id="email" name="email" placeholder="john@example.com" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" id="confirm_password" placeholder="••••••••" required>
                </div>
            </div>

            <div class="form-group">
                <label>Professional Bio / Details</label>
                <textarea id="bio" name="bio" placeholder="Tell us about your experience or travel interests..."></textarea>
            </div>

            <button type="submit" class="btn-signup">REGISTER NOW</button>

            <div class="footer-text">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const fullname = document.getElementById('fullname').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirm_password = document.getElementById('confirm_password').value;
            const role = document.getElementById('role').value;
            const bio = document.getElementById('bio').value;

            if (password !== confirm_password) {
                alert("Passwords do not match!");
                return;
            }

            const formData = {
                fullname: fullname,
                email: email,
                password: password,
                role: role,
                bio: bio
            };

            // Using ../ to go up from frontend/ and into backend/
            fetch('../backend/auth_signup.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    alert("Success: " + data.message);
                    window.location.href = 'login.php'; 
                } else {
                    alert("Registration Failed: " + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Error connecting to server. Please check your file paths and XAMPP status.");
            });
        });
    </script>
</body>
</html>