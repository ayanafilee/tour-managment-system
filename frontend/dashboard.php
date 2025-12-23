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
    <title>Admin | Dashboard</title>
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

        /* Sidebar Styling - Matching Users and Bookings exactly */
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
        .main { margin-left: 250px; padding: 40px; width: calc(100% - 250px); box-sizing: border-box; }
        
        /* Stat Cards Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); position: relative; overflow: hidden; }
        .stat-card h3 { margin: 0; color: #7f8c8d; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; }
        .stat-card p { margin: 10px 0 0; font-size: 32px; font-weight: bold; color: var(--primary); }
        .stat-card i { position: absolute; right: 20px; bottom: 20px; font-size: 40px; color: rgba(0,0,0,0.05); }
        
        .card-blue { border-top: 4px solid var(--accent); }
        .card-green { border-top: 4px solid var(--success); }
        .card-yellow { border-top: 4px solid var(--warning); }

        /* Recent Activity Table */
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h2.section-title { margin-top: 0; color: var(--primary); font-size: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 15px; border-bottom: 1px solid #eee; }
        th { color: #7f8c8d; font-weight: 600; font-size: 14px; text-transform: uppercase; }
        
        .status-pill { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .status-confirmed { background: #e8f5e9; color: #2e7d32; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Admin Panel</h2>
        <hr style="border: 0; border-top: 1px solid #34495e; margin-bottom: 20px;">
        
        <p style="font-size: 12px; color: #7f8c8d; margin-left: 15px;">MAIN NAVIGATION</p>
        
        <a href="dashboard.php" class="nav-link active">
            <i class="fas fa-chart-line"></i> Dashboard
        </a>
        
        <a href="manage_users.php" class="nav-link">
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

    <div class="main">
        <h1 style="color: var(--primary);">Welcome, Admin</h1>
        <p style="color: #7f8c8d;">Here is what's happening with TourConnect today.</p>
        
        <div class="stats-grid">
            <div class="stat-card card-blue">
                <h3>Total Tourists</h3>
                <p id="stat-tourists">0</p>
                <i class="fas fa-user-friends"></i>
            </div>
            <div class="stat-card card-blue">
                <h3>Total Guides</h3>
                <p id="stat-guides">0</p>
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-card card-yellow">
                <h3>Total Bookings</h3>
                <p id="stat-bookings">0</p>
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <div class="stat-card card-green">
                <h3>Total Revenue</h3>
                <p id="stat-revenue">$0.00</p>
                <i class="fas fa-wallet"></i>
            </div>
        </div>

        <div class="card">
            <h2 class="section-title">Recent Activity (Service Links)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Tourist</th>
                        <th>Price</th>
                        <th>Hotel (G2)</th>
                        <th>Taxi (G4)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="recentBookings">
                    <tr><td colspan="6" style="text-align:center;">Fetching latest activity...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('../backend/admin_stats.php')
                .then(res => res.json())
                .then(data => {
                    // Update Stat Numbers
                    document.getElementById('stat-tourists').innerText = data.tourists;
                    document.getElementById('stat-guides').innerText = data.guides;
                    document.getElementById('stat-bookings').innerText = data.bookings;
                    document.getElementById('stat-revenue').innerText = '$' + parseFloat(data.revenue).toLocaleString();

                    // Update Table
                    const tbody = document.getElementById('recentBookings');
                    tbody.innerHTML = '';
                    
                    if (!data.recent || data.recent.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">No recent bookings found.</td></tr>';
                        return;
                    }

                    data.recent.forEach(b => {
                        tbody.innerHTML += `
                            <tr>
                                <td><b>#BK-${b.id}</b></td>
                                <td>${b.tourist_name}</td>
                                <td>$${b.price}</td>
                                <td>${b.hotel_id ? '<span style="color:var(--success)">✅ ' + b.hotel_id + '</span>' : '<span style="color:var(--danger)">❌ Pending</span>'}</td>
                                <td>${b.taxi_id ? '<span style="color:var(--success)">✅ ' + b.taxi_id + '</span>' : '<span style="color:var(--danger)">❌ Pending</span>'}</td>
                                <td><span class="status-pill status-confirmed">${b.status}</span></td>
                            </tr>
                        `;
                    });
                })
                .catch(err => {
                    console.error("Error loading stats:", err);
                    document.getElementById('recentBookings').innerHTML = '<tr><td colspan="6" style="text-align:center; color:red;">Failed to connect to backend.</td></tr>';
                });
        });
    </script>
</body>
</html>