<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TourConnect | Explore the World with Group 1</title>
    <style>
        :root {
            --primary: #2c3e50;
            --accent: #3498db;
            --light: #f8f9fa;
        }

        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            scroll-behavior: smooth;
        }

        /* Hero Section with Background */
        .hero {
            height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                        url('https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        /* Navigation Bar */
        nav {
            position: absolute;
            top: 0;
            width: 100%;
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
            background: rgba(0,0,0,0.2);
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
        }

        /* The Requested Signup Button */
        .btn-signup-nav {
            background-color: var(--accent);
            color: white !important;
            padding: 10px 25px;
            border-radius: 25px;
            transition: 0.3s;
            border: 2px solid var(--accent);
        }

        .btn-signup-nav:hover {
            background-color: transparent;
        }

        /* Hero Content */
        .hero h1 {
            font-size: 60px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .hero p {
            font-size: 20px;
            margin-bottom: 30px;
            max-width: 600px;
        }

        .btn-main {
            padding: 15px 40px;
            background: white;
            color: var(--primary);
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: 0.3s;
        }

        .btn-main:hover {
            background: var(--accent);
            color: white;
        }

        /* Features Section */
        .features {
            padding: 80px 20px;
            display: flex;
            justify-content: center;
            gap: 40px;
            background: var(--light);
        }

        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 250px;
            text-align: center;
        }

        .feature-card h3 { color: var(--accent); }
    </style>
</head>
<body>

    <nav>
        <div class="logo">TOURCONNECT</div>
        <div class="nav-links">
            <a href="login.php">Login</a>
            <a href="signup.php" class="btn-signup-nav">Get Started</a>
        </div>
    </nav>

    <section class="hero">
        <h1>Your Journey Starts Here</h1>
        <p>Connecting expert tour guides with passionate travelers. Join Group 1's premier tourism network today.</p>
        <a href="#about" class="btn-main">Learn More</a>
    </section>

    <section id="about" class="features">
        <div class="feature-card">
            <h3>Expert Guides</h3>
            <p>Our providers are certified professionals with years of local knowledge.</p>
        </div>
        <div class="feature-card">
            <h3>Seamless Booking</h3>
            <p>Integrated with hotel and transport services for a smooth experience.</p>
        </div>
        <div class="feature-card">
            <h3>Unique Places</h3>
            <p>Access hidden gems and exclusive tours you won't find anywhere else.</p>
        </div>
    </section>

</body>
</html>