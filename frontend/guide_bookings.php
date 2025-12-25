<?php
session_start();
// Security Check: Only allow logged-in Guides
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'guide') {
    header("Location: ./login.php"); // Ensure path back to login is correct
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings | Guide Portal</title>
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

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg);
            margin: 0;
            display: flex;
        }

        /* Sidebar Styling - Exactly matches Admin Panel */
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

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        h1 { color: var(--primary); margin-top: 0; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 15px; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; color: #7f8c8d; font-size: 14px; text-transform: uppercase; }

        /* Status Badges */
        .status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-confirmed { background: #e8f8f0; color: var(--success); }
        .status-pending { background: #fff9e6; color: var(--warning); }
        .status-cancelled { background: #feeae9; color: var(--danger); }

        .price-tag { font-weight: bold; color: var(--primary); }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>TourConnect</h2>
        <hr style="border: 0; border-top: 1px solid #34495e; margin-bottom: 20px;">
        
        <p style="font-size: 12px; color: #7f8c8d; margin-left: 15px;">GUIDE MENU</p>
        
        <a href="add_tour.php" class="nav-link">
            <i class="fas fa-plus-circle"></i> Create Tour
        </a>
        
        <a href="guide_bookings.php" class="nav-link active">
            <i class="fas fa-clipboard-list"></i> View Bookings
        </a>

        <div style="margin-top: 50px;">
            <p style="font-size: 12px; color: #7f8c8d; margin-left: 15px;">ACCOUNT</p>
            <p style="font-size: 13px; color: #bdc3c7; margin-left: 15px; margin-bottom: 10px;">
                <i class="fas fa-user-circle"></i> <?php echo $_SESSION['user_name']; ?>
            </p>
            <a href="../backend/logout.php" class="nav-link" style="color: #ff7675;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <div class="main-content">
        <h1>Tour Bookings</h1>
        <p style="color: #7f8c8d;">Manage reservations for the tours you have published.</p>

        <div class="card">
            <table id="bookingsTable">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Tour Title</th>
                        <th>Tourist Name</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="bookingsBody">
                    <tr><td colspan="6" style="text-align:center;">Loading bookings...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('../backend/get_guide_bookings.php')
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('bookingsBody');
                tbody.innerHTML = '';

                if(data.status === 'success' && data.data.length > 0) {
                    data.data.forEach(booking => {
                        tbody.innerHTML += `
                            <tr>
                                <td><b>#BK-${booking.booking_id}</b></td>
                                <td>${booking.title}</td>
                                <td>${booking.tourist_name}</td>
                                <td>${booking.booking_date}</td>
                                <td class="price-tag">$${parseFloat(booking.total_price).toFixed(2)}</td>
                                <td>
                                    <span class="status status-${booking.status.toLowerCase()}">
                                        ${booking.status}
                                    </span>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">No bookings found yet.</td></tr>';
                }
            })
            .catch(err => {
                console.error("Error loading bookings:", err);
                document.getElementById('bookingsBody').innerHTML = '<tr><td colspan="6" style="text-align:center; color:red;">Connection error.</td></tr>';
            });
        });
    </script>
</body>
</html>