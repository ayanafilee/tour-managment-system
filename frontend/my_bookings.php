<?php
session_start();
// Security: Only allow tourists to see their own bookings
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'tourist') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings | TourConnect</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #2c3e50;
            --accent: #3498db;
            --success: #27ae60;
            --danger: #e74c3c;
            --bg: #f4f7f6;
            --white: #ffffff;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg);
            margin: 0;
            display: flex;
        }

        /* Sidebar Navigation - Shared across all dashboard pages */
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

        h1 { color: var(--primary); margin-top: 0; }

        .booking-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 6px solid var(--accent);
            transition: 0.3s;
        }
        
        .booking-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.1); }

        .booking-details h3 { margin: 0 0 10px 0; color: var(--primary); font-size: 1.3rem; }
        .booking-details p { margin: 5px 0; font-size: 14px; color: #7f8c8d; display: flex; align-items: center; }
        .booking-details p i { width: 20px; color: var(--accent); margin-right: 8px; }

        .service-refs {
            margin-top: 15px;
            display: flex;
            gap: 12px;
        }

        .ref-badge {
            background: #f8f9fa;
            padding: 5px 12px;
            border-radius: 6px;
            color: #555;
            border: 1px solid #eee;
            font-size: 11px;
            font-weight: 600;
        }

        .status-badge {
            padding: 8px 18px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-confirmed { background: #e8f8f0; color: var(--success); }
        .status-cancelled { background: #feeae9; color: var(--danger); }

        .btn-cancel {
            background: transparent;
            color: var(--danger);
            border: 1px solid var(--danger);
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
            font-size: 13px;
            margin-top: 15px;
        }

        .btn-cancel:hover { background: var(--danger); color: white; }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 15px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>TourConnect</h2>
        <hr style="border: 0; border-top: 1px solid #34495e; margin-bottom: 20px;">
        
        <p style="font-size: 12px; color: #7f8c8d; margin-left: 15px;">MENU</p>
        <a href="tourist_home.php" class="nav-link">
            <i class="fas fa-search"></i> Explore Tours
        </a>
        <a href="my_bookings.php" class="nav-link active">
            <i class="fas fa-ticket-alt"></i> My Bookings
        </a>

        <div style="margin-top: 50px;">
            <p style="font-size: 12px; color: #7f8c8d; margin-left: 15px;">ACCOUNT</p>
            <a href="profile.php" class="nav-link">
                <i class="fas fa-user-circle"></i> Profile Settings
            </a>
            <a href="../backend/logout.php" class="nav-link" style="color: #ff7675;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <div class="main-content">
        <h1>My Travel Plans</h1>
        <p style="color: #7f8c8d; margin-bottom: 30px;">Track your confirmed itineraries and service connections.</p>

        <div id="bookingsList">
            <p><i class="fas fa-spinner fa-spin"></i> Retrieving your bookings...</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', fetchBookings);

        function fetchBookings() {
            fetch('../backend/get_customer_bookings.php')
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('bookingsList');
                    container.innerHTML = '';

                    if (data.status === 'success' && data.data.length > 0) {
                        data.data.forEach(booking => {
                            const isCancelled = booking.status.toLowerCase() === 'cancelled';
                            container.innerHTML += `
                                <div class="booking-card" style="${isCancelled ? 'border-left-color: #bdc3c7; opacity: 0.8;' : ''}">
                                    <div class="booking-details">
                                        <h3>${booking.title}</h3>
                                        <p><i class="fas fa-map-marker-alt"></i> ${booking.destination}</p>
                                        <p><i class="fas fa-calendar-alt"></i> Booked on: ${booking.booking_date}</p>
                                        <p><i class="fas fa-wallet"></i> Total Paid: <strong>$${parseFloat(booking.total_price).toFixed(2)}</strong></p>
                                        
                                        <div class="service-refs">
                                            <span class="ref-badge"><i class="fas fa-hotel"></i> ${booking.hotel_id_ref}</span>
                                            <span class="ref-badge"><i class="fas fa-taxi"></i> ${booking.taxi_id_ref}</span>
                                        </div>
                                    </div>

                                    <div style="text-align: right; min-width: 150px;">
                                        <span class="status-badge status-${booking.status.toLowerCase()}">
                                            <i class="fas ${isCancelled ? 'fa-times-circle' : 'fa-check-circle'}"></i> ${booking.status}
                                        </span>
                                        <br>
                                        ${!isCancelled ? 
                                            `<button class="btn-cancel" onclick="cancelBooking(${booking.booking_id}, '${booking.title.replace(/'/g, "\\'")}')">
                                                Cancel Trip
                                            </button>` 
                                            : `<p style="font-size: 11px; color: #95a5a6; margin-top: 15px;">Reservation Inactive</p>`}
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        container.innerHTML = `
                            <div class="empty-state">
                                <i class="fas fa-suitcase-rolling fa-4x" style="margin-bottom: 20px; color: #dcdde1;"></i>
                                <h2>No trips found!</h2>
                                <p>You haven't booked any amazing experiences yet.</p>
                                <br>
                                <a href="tourist_home.php" class="btn-cancel" style="text-decoration: none; border-color: var(--accent); color: var(--accent);">
                                    Find My Next Adventure
                                </a>
                            </div>`;
                    }
                });
        }

        function cancelBooking(id, title) {
            Swal.fire({
                title: 'Cancel this trip?',
                text: `Are you sure you want to cancel your booking for "${title}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger)',
                cancelButtonColor: 'var(--primary)',
                confirmButtonText: 'Yes, cancel it',
                cancelButtonText: 'Keep booking'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Cancelling...',
                        didOpen: () => { Swal.showLoading() },
                        allowOutsideClick: false
                    });

                    fetch('../backend/cancel_booking.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ booking_id: id })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Cancelled!', data.message, 'success');
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                        fetchBookings(); // Refresh list
                    })
                    .catch(err => {
                        Swal.fire('Error', 'Communication with server failed.', 'error');
                    });
                }
            });
        }
    </script>
</body>
</html>