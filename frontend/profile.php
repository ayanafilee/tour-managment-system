<?php
session_start();
// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | TourConnect</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #2c3e50;
            --accent: #3498db;
            --bg: #f4f7f6;
            --white: #ffffff;
            --danger: #e74c3c;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg);
            margin: 0;
            display: flex;
        }

        /* Sidebar - Matches Dashboard and Bookings */
        .sidebar { 
            width: 250px; 
            height: 100vh; 
            background: var(--primary); 
            color: white; 
            position: fixed; 
            padding: 20px; 
            box-sizing: border-box;
        }
        
        .sidebar h2 { color: var(--accent); font-size: 22px; margin-bottom: 20px; text-align: center; }
        
        .nav-link { 
            color: #bdc3c7; 
            text-decoration: none; 
            display: block; 
            padding: 12px 15px; 
            border-radius: 8px; 
            margin-bottom: 10px;
            transition: 0.3s;
            font-size: 16px;
        }

        .nav-link i { margin-right: 10px; width: 20px; text-align: center; }
        .nav-link:hover { background: #34495e; color: white; }

        .nav-link.active { 
            background: var(--accent); 
            color: white !important; 
            font-weight: bold; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 40px;
            width: calc(100% - 250px);
            box-sizing: border-box;
        }

        .header-section { margin-bottom: 30px; }
        h1 { color: var(--primary); margin: 0; }

        .card {
            background: var(--white);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            max-width: 550px;
            margin-bottom: 30px;
            border-top: 4px solid var(--primary);
        }

        .card h2 { 
            color: var(--primary); 
            margin-top: 0; 
            font-size: 1.2rem; 
            display: flex; 
            align-items: center;
            gap: 10px;
        }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; font-size: 14px; }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            background: #fdfdfd;
            transition: 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .btn-update {
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-update:hover { background: #34495e; transform: translateY(-1px); }

        .info-box {
            background: #e1f5fe;
            color: #01579b;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>TourConnect</h2>
        <hr style="border: 0; border-top: 1px solid #34495e; margin-bottom: 20px;">
        
        <p style="font-size: 12px; color: #7f8c8d; margin-left: 15px;">EXPLORE</p>
        <a href="tourist_home.php" class="nav-link">
            <i class="fas fa-globe-americas"></i> Explore Tours
        </a>
        <a href="my_bookings.php" class="nav-link">
            <i class="fas fa-calendar-check"></i> My Bookings
        </a>

        <div style="margin-top: 50px;">
            <p style="font-size: 12px; color: #7f8c8d; margin-left: 15px;">ACCOUNT</p>
            <a href="profile.php" class="nav-link active">
                <i class="fas fa-user-cog"></i> Profile Settings
            </a>
            <a href="../backend/logout.php" class="nav-link" style="color: #ff7675;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="header-section">
            <h1 id="welcomeTitle">Profile Settings</h1>
            <p style="color: #7f8c8d;">Update your account security and personal information.</p>
        </div>

        <div class="card">
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <span>Manage your login credentials for <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>.</span>
            </div>

            <h2><i class="fas fa-shield-alt"></i> Password Security</h2>
            <form id="passwordForm">
                <div class="form-group">
                    <label><i class="fas fa-key"></i> New Password</label>
                    <input type="password" id="new_password" placeholder="Minimum 6 characters" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-check-double"></i> Confirm New Password</label>
                    <input type="password" id="confirm_password" placeholder="Repeat new password" required>
                </div>
                <button type="submit" class="btn-update">Update Password</button>
            </form>
        </div>
    </div>

    <script>
        // Change Password Logic
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const pass = document.getElementById('new_password').value;
            const confirm = document.getElementById('confirm_password').value;

            if(pass.length < 6) {
                return Swal.fire('Error', 'Password must be at least 6 characters long.', 'warning');
            }

            if(pass !== confirm) {
                return Swal.fire('Error', 'Passwords do not match!', 'error');
            }

            Swal.fire({
                title: 'Updating password...',
                didOpen: () => { Swal.showLoading() },
                allowOutsideClick: false
            });

            fetch('../backend/change_password.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ password: pass })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: 'Your security settings have been updated.',
                        confirmButtonColor: '#2c3e50'
                    });
                    document.getElementById('passwordForm').reset();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Failed to connect to the server.', 'error');
            });
        });
    </script>
</body>
</html>