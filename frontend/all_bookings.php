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
    <title>Admin | Global Bookings</title>
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

        /* Sidebar Styling */
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

        /* This handles the "Active" state */
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

        /* Buttons & Badges */
        .btn-link { background: var(--warning); color: #000; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: bold; transition: 0.2s; }
        .btn-link:hover { background: #d4ac0d; transform: translateY(-2px); }

        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .pending { background: #fff4e5; color: #b7791f; }
        .confirmed { background: #e6fffa; color: #2c7a7b; }
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
    
    <a href="manage_users.php" class="nav-link">
        <i class="fas fa-users"></i> Manage Users
    </a>
    
    <a href="all_bookings.php" class="nav-link active">
        <i class="fas fa-calendar-check"></i> All Bookings
    </a>
    
    <div style="margin-top: 50px;">
        <p style="font-size: 12px; color: #7f8c8d; margin-left: 15px;">SYSTEM</p>
        <a href="../backend/logout.php" class="nav-link" style="color: #ff7675;">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<div class="main-content">
    <h1 style="color: var(--primary);">Global Booking Management</h1>
    <p style="color: #7f8c8d;">Assign Hotel and Taxi IDs to tourist bookings below.</p>
    
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tourist</th>
                    <th>Tour Title</th>
                    <th>Hotel ID (G2)</th>
                    <th>Taxi ID (G4)</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="bookingsTable">
                </tbody>
        </table>
    </div>
</div>

<script>
function loadBookings() {
    const tbody = document.getElementById('bookingsTable');
    tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">Loading bookings...</td></tr>';
    
    fetch('../backend/admin_get_bookings.php')
    .then(res => res.json())
    .then(data => {
        tbody.innerHTML = '';
        
        if (data.error) {
            tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; color:red;">Error: ${data.error}</td></tr>`;
            return;
        }
        
        if (!Array.isArray(data) || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">No bookings found.</td></tr>';
            return;
        }
        
        data.forEach(b => {
            const hotelText = b.hotel_id_ref ? b.hotel_id_ref : '<span style="color:#bdc3c7">---</span>';
            const taxiText = b.taxi_id_ref ? b.taxi_id_ref : '<span style="color:#bdc3c7">---</span>';
            const statusClass = b.status === 'confirmed' ? 'confirmed' : 'pending';

            tbody.innerHTML += `
                <tr>
                    <td><b>#BK-${b.booking_id}</b></td>
                    <td>${b.tourist_name || 'N/A'}</td>
                    <td>${b.tour_title || 'N/A'}</td>
                    <td><code>${hotelText}</code></td>
                    <td><code>${taxiText}</code></td>
                    <td><span class="status-badge ${statusClass}">${b.status}</span></td>
                    <td>
                        <button class="btn-link" onclick="openLinkModal(${b.booking_id}, '${b.hotel_id_ref || ''}', '${b.taxi_id_ref || ''}')">
                           <i class="fas fa-link"></i> Add Link
                        </button>
                    </td>
                </tr>
            `;
        });
    })
    .catch(err => {
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; color:red;">Connection Failed: Check Backend Path</td></tr>`;
    });
}

function openLinkModal(bookingId, currentHotel, currentTaxi) {
    Swal.fire({
        title: 'Connect Service IDs',
        text: `Update references for Booking #${bookingId}`,
        html: `
            <input id="swal-hotel" class="swal2-input" placeholder="Hotel ID (Group 2)" value="${currentHotel}">
            <input id="swal-taxi" class="swal2-input" placeholder="Taxi ID (Group 4)" value="${currentTaxi}">
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonColor: '#3498db',
        confirmButtonText: 'Save Links',
        preConfirm: () => {
            return {
                hotel: document.getElementById('swal-hotel').value,
                taxi: document.getElementById('swal-taxi').value
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../backend/admin_update_links.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    booking_id: bookingId,
                    hotel_id: result.value.hotel,
                    taxi_id: result.value.taxi
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated',
                        text: 'Service IDs linked successfully.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    loadBookings();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        }
    });
}

// Initial Load
document.addEventListener('DOMContentLoaded', loadBookings);
</script>
</body>
</html>