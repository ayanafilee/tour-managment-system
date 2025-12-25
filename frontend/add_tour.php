<?php
session_start();
// Security Check: Only allow logged-in Guides
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'guide') {
    header("Location: ./login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guide Dashboard | Manage Tours</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        /* Sidebar Styling - Exactly matching Admin Panel and Bookings */
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
        .content {
            margin-left: 250px;
            padding: 40px;
            width: calc(100% - 250px);
            box-sizing: border-box;
        }

        h1 { color: var(--primary); margin-top: 0; }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .card h3 { margin-top: 0; color: var(--primary); border-bottom: 2px solid #f4f7f6; padding-bottom: 10px; }

        /* Form Styling */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #34495e; font-size: 14px; }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-family: inherit;
        }
        .form-group input:focus, .form-group textarea:focus { border-color: var(--accent); outline: none; }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
            text-transform: uppercase;
            font-size: 13px;
        }

        .btn-submit { background: var(--accent); color: white; width: 100%; margin-top: 10px; }
        .btn-submit:hover { background: #2980b9; }

        .btn-edit { background: var(--accent); color: white; margin-right: 5px; }
        .btn-delete { background: #fff1f0; color: var(--danger); border: 1px solid var(--danger); }
        .btn-delete:hover { background: var(--danger); color: white; }

        /* Table Styling */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; padding: 15px; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; color: #7f8c8d; font-size: 13px; text-transform: uppercase; }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 500px;
            max-width: 90%;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>TourConnect</h2>
        <hr style="border: 0; border-top: 1px solid #34495e; margin-bottom: 20px;">
        
        <p style="font-size: 12px; color: #7f8c8d; margin-left: 15px;">GUIDE MENU</p>
        
        <a href="add_tour.php" class="nav-link active">
            <i class="fas fa-plus-circle"></i> Create Tour
        </a>
        
        <a href="guide_bookings.php" class="nav-link">
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

    <div class="content">
        <h1>Tour Management</h1>
        <p style="color: #7f8c8d;">Create and manage your professional tour packages.</p>

        <div class="card">
            <h3>Create a New Package</h3>
            <form id="addTourForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Tour Title</label>
                        <input type="text" id="title" placeholder="e.g. Historic City Walk" required>
                    </div>
                    <div class="form-group">
                        <label>Destination</label>
                        <input type="text" id="destination" placeholder="e.g. Rome, Italy" required>
                    </div>
                </div>
                <div class="form-group" style="max-width: 200px;">
                    <label>Price ($)</label>
                    <input type="number" id="price" placeholder="99.00" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea id="description" rows="3" placeholder="Describe the amazing journey..."></textarea>
                </div>
                <button type="submit" class="btn btn-submit">CREATE TOUR PACKAGE</button>
            </form>
        </div>

        <div class="card">
            <h3>Your Published Tours</h3>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Destination</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="toursBody">
                    <tr><td colspan="4" style="text-align:center;">Loading your tours...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>Edit Tour Details</h3>
            <form id="editTourForm">
                <input type="hidden" id="edit_tour_id">
                <div class="form-group">
                    <label>Tour Title</label>
                    <input type="text" id="edit_title" required>
                </div>
                <div class="form-group">
                    <label>Destination</label>
                    <input type="text" id="edit_destination" required>
                </div>
                <div class="form-group">
                    <label>Price ($)</label>
                    <input type="number" id="edit_price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea id="edit_description" rows="3"></textarea>
                </div>
                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn btn-submit" style="background:var(--success); flex:2;">UPDATE TOUR</button>
                    <button type="button" class="btn" style="background:#bdc3c7; color:white; flex:1;" onclick="closeEditModal()">CANCEL</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // --- 1. Load Tours ---
        function loadMyTours() {
            fetch('../backend/get_my_tours.php')
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('toursBody');
                tbody.innerHTML = '';
                if(data.status === 'success' && data.data.length > 0) {
                    data.data.forEach(tour => {
                        const tourData = JSON.stringify(tour).replace(/'/g, "&apos;");
                        tbody.innerHTML += `
                            <tr>
                                <td><strong>${tour.title}</strong></td>
                                <td>${tour.destination}</td>
                                <td><b>$${parseFloat(tour.price).toFixed(2)}</b></td>
                                <td>
                                    <button class="btn btn-edit" onclick='openEditModal(${tourData})'>
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-delete" onclick="deleteTour(${tour.tour_id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">No tours published yet.</td></tr>';
                }
            });
        }

        // --- 2. Add New Tour ---
        document.getElementById('addTourForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const payload = {
                title: document.getElementById('title').value,
                destination: document.getElementById('destination').value,
                price: document.getElementById('price').value,
                description: document.getElementById('description').value
            };
            fetch('../backend/add_tour.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                Swal.fire({
                    icon: data.status === 'success' ? 'success' : 'error',
                    title: data.status === 'success' ? 'Created!' : 'Oops...',
                    text: data.message,
                    confirmButtonColor: 'var(--accent)'
                });

                if(data.status === 'success') {
                    document.getElementById('addTourForm').reset();
                    loadMyTours();
                }
            });
        });

        // --- 3. Edit Functionality ---
        function openEditModal(tour) {
            document.getElementById('edit_tour_id').value = tour.tour_id;
            document.getElementById('edit_title').value = tour.title;
            document.getElementById('edit_destination').value = tour.destination;
            document.getElementById('edit_price').value = tour.price;
            document.getElementById('edit_description').value = tour.description;
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        document.getElementById('editTourForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const payload = {
                tour_id: document.getElementById('edit_tour_id').value,
                title: document.getElementById('edit_title').value,
                destination: document.getElementById('edit_destination').value,
                price: document.getElementById('edit_price').value,
                description: document.getElementById('edit_description').value
            };
            fetch('../backend/edit_tour.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                Swal.fire({
                    icon: data.status === 'success' ? 'success' : 'error',
                    title: data.status === 'success' ? 'Updated!' : 'Error',
                    text: data.message,
                    confirmButtonColor: 'var(--success)'
                });

                if(data.status === 'success') {
                    closeEditModal();
                    loadMyTours();
                }
            });
        });

        // --- 4. Delete Functionality ---
        function deleteTour(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently remove this tour package.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger)',
                cancelButtonColor: 'var(--primary)',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('../backend/delete_tour.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ tour_id: id })
                    })
                    .then(res => res.json())
                    .then(data => {
                        Swal.fire({
                            title: data.status === 'success' ? 'Deleted!' : 'Error',
                            text: data.message,
                            icon: data.status === 'success' ? 'success' : 'error'
                        });
                        loadMyTours();
                    });
                }
            });
        }

        // Run on Page Load
        loadMyTours();
    </script>
</body>
</html>