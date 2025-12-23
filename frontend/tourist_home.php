<?php
session_start();
// Redirect to login if not logged in or not a tourist
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
    <title>Tourist Dashboard | TourConnect</title>
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

        /* Sidebar Navigation - Matches Admin/Guide */
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

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        h1 { color: var(--primary); margin: 0; }

        /* Tour Cards Grid */
        .tour-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }

        .tour-card {
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: 0.3s;
            display: flex;
            flex-direction: column;
            border: 1px solid #eee;
        }

        .tour-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }

        .tour-info { padding: 25px; flex-grow: 1; }
        .tour-info h3 { margin: 0 0 10px 0; color: var(--primary); font-size: 20px; }
        .tour-info p { color: #7f8c8d; font-size: 14px; line-height: 1.6; height: 65px; overflow: hidden; }

        .tour-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .price { color: var(--success); font-size: 22px; font-weight: bold; }

        .btn-book {
            width: 100%;
            padding: 14px;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 8px;
            margin-top: 20px;
            cursor: pointer;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background 0.2s;
        }

        .btn-book:hover { background: #2980b9; }

        .location-badge {
            display: inline-block;
            background: #e1f5fe;
            color: #01579b;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .user-badge {
            background: var(--accent);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>TourConnect</h2>
        <hr style="border: 0; border-top: 1px solid #34495e; margin-bottom: 20px;">
        
        <p style="font-size: 12px; color: #7f8c8d; margin-left: 15px;">EXPLORE</p>
        
        <a href="tourist_home.php" class="nav-link active">
            <i class="fas fa-globe-americas"></i> Explore Tours
        </a>
        
        <a href="my_bookings.php" class="nav-link">
            <i class="fas fa-calendar-check"></i> My Bookings
        </a>

        <div style="margin-top: 50px;">
            <p style="font-size: 12px; color: #7f8c8d; margin-left: 15px;">ACCOUNT</p>
            <a href="profile.php" class="nav-link">
                <i class="fas fa-user-cog"></i> Profile Settings
            </a>
            <a href="../backend/logout.php" class="nav-link" style="color: #ff7675;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <div>
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
                <p style="color: #7f8c8d; margin: 5px 0 0 0;">Where would you like to go next?</p>
            </div>
            <span class="user-badge"><i class="fas fa-star"></i> Tourist</span>
        </div>

        <div class="tour-grid" id="tourContainer">
            <p>Searching for the best experiences...</p>
        </div>
    </div>

    <script>
        // Fetch Tours from Backend
        document.addEventListener('DOMContentLoaded', function() {
            fetch('../backend/get_all_tours.php')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('tourContainer');
                    container.innerHTML = ''; 

                    if (data.status === 'success' && data.data.length > 0) {
                        data.data.forEach(tour => {
                            container.innerHTML += `
                                <div class="tour-card">
                                    <div class="tour-info">
                                        <div class="location-badge"><i class="fas fa-map-marker-alt"></i> ${tour.destination}</div>
                                        <h3>${tour.title}</h3>
                                        <p>${tour.description.substring(0, 120)}...</p>
                                        <div class="tour-meta">
                                            <span class="price">$${parseFloat(tour.price).toFixed(2)}</span>
                                            <span style="font-size: 12px; color: #95a5a6;">per person</span>
                                        </div>
                                        <button class="btn-book" onclick="bookTour(${tour.tour_id}, '${tour.title.replace(/'/g, "\\'")}', ${tour.price})">
                                            BOOK THIS TOUR
                                        </button>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        container.innerHTML = '<div class="card" style="grid-column: 1/-1; text-align:center; padding: 50px;"><h3>No tours available at the moment.</h3><p>Check back later!</p></div>';
                    }
                })
                .catch(err => {
                    console.error("Fetch Error:", err);
                    document.getElementById('tourContainer').innerHTML = '<p style="color:red;">Failed to connect to the travel database.</p>';
                });
        });

        // Booking Functionality
        function bookTour(tourId, tourTitle, price) {
            Swal.fire({
                title: 'Confirm Booking',
                html: `Reserve your spot for <b>${tourTitle}</b><br><br><span style="font-size: 24px; color: #27ae60; font-weight:bold;">$${price}</span>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3498db',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Confirm Reservation',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Securing your spot...',
                        didOpen: () => { Swal.showLoading() },
                        allowOutsideClick: false
                    });

                    const bookingData = {
                        tour_id: tourId,
                        total_price: price,
                        hotel_id_ref: "G2-HOTEL-PENDING",
                        taxi_id_ref: "G4-TAXI-PENDING"
                    };

                    fetch('../backend/book_tour.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(bookingData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Tour Booked!',
                                text: data.message,
                                confirmButtonColor: '#27ae60'
                            }).then(() => {
                                window.location.href = 'my_bookings.php';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Booking Failed',
                                text: data.message,
                                confirmButtonColor: '#e74c3c'
                            });
                        }
                    })
                    .catch(err => {
                        Swal.fire('Error', 'Server connection error.', 'error');
                        console.error("Booking Error:", err);
                    });
                }
            });
        }
    </script>
</body>
</html>