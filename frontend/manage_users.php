<?php
session_start();
// Security: Only allow 'admin' role
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | User Management</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { 
            --primary: #2c3e50; 
            --accent: #3498db; 
            --success: #27ae60; 
            --warning: #f1c40f;
            --danger: #e74c3c;
            --bg: #f4f7f6; 
        }

        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); margin: 0; display: flex; }

        /* Sidebar Styling - Exactly the same as Bookings */
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

        /* Main Content Styling */
        .main-content { margin-left: 250px; padding: 40px; width: calc(100% - 250px); box-sizing: border-box; }
        
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; color: #7f8c8d; font-size: 14px; text-transform: uppercase; }

        /* Badges & Actions */
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .role-guide { background: #e3f2fd; color: #1976d2; }
        .role-tourist { background: #f1f8e9; color: #388e3c; }
        
        .btn-del { color: var(--danger); cursor: pointer; border: none; background: none; font-weight: bold; font-size: 14px; }
        .btn-del:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <hr style="border: 0; border-top: 1px solid #34495e; margin-bottom: 20px;">
    
    <p style="font-size: 12px; color: #7f8c8d; margin-left: 15px;">MAIN NAVIGATION</p>
    
    <a href="dashboard.php" class="nav-link">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
    
    <a href="manage_users.php" class="nav-link active">
        <i class="fas fa-users"></i> Manage Users
    </a>
    
    <a href="all_bookings.php" class="nav-link">
        <i class="fas fa-calendar-check"></i> All Bookings
    </a>
    
    <div style="margin-top: 50px;">
        <p style="font-size: 12px; color: #7f8c8d; margin-left: 15px;">SYSTEM</p>
        <p style="font-size: 13px; color: #bdc3c7; margin-left: 15px; margin-bottom: 10px;">
            <i class="fas fa-user-circle"></i> <?php echo $_SESSION['user_name']; ?>
        </p>
        <a href="../backend/logout.php" class="nav-link" style="color: #ff7675;">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<div class="main-content">
    <h1 style="color: var(--primary);">User Management</h1>
    <p style="color: #7f8c8d;">View and manage all registered Tourists and Guides.</p>

    <div class="card">
        <h3>System Users</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                </tbody>
        </table>
    </div>
</div>

<script>
function loadUsers() {
    const tbody = document.getElementById('userTableBody');
    tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;">Searching for users...</td></tr>';

    fetch('../backend/admin_get_users.php') 
    .then(res => {
        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return res.json();
    })
    .then(data => {
        tbody.innerHTML = '';
        
        if (data.error) {
            tbody.innerHTML = `<tr><td colspan="5" style="color:var(--danger); text-align:center;">Error: ${data.error}</td></tr>`;
            return;
        }

        if (!Array.isArray(data) || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;">No users found.</td></tr>';
            return;
        }

        data.forEach(user => {
            const displayId = user.id || user.user_id; 
            const roleClass = user.role.toLowerCase() === 'guide' ? 'role-guide' : 'role-tourist';
            
            tbody.innerHTML += `
                <tr>
                    <td><b>${displayId}</b></td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td><span class="badge ${roleClass}">${user.role.toUpperCase()}</span></td>
                    <td>
                        <button class="btn-del" onclick="deleteUser(${displayId})">
                           <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
            `;
        });
    })
    .catch(err => {
        console.error("Fetch error:", err);
        tbody.innerHTML = `<tr><td colspan="5" style="color:var(--danger); text-align:center;">Connection Failed: ${err.message}</td></tr>`;
    });
}

function deleteUser(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will permanently remove the user and their data!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'var(--danger)',
        cancelButtonColor: '#95a5a6',
        confirmButtonText: 'Yes, delete!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../backend/admin_delete_user.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ user_id: id })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire('Deleted!', data.message, 'success');
                    loadUsers();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => Swal.fire('Error', 'Server communication failed', 'error'));
        }
    });
}

document.addEventListener('DOMContentLoaded', loadUsers);
</script>
</body>
</html>