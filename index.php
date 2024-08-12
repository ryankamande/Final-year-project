<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HMS Garage Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f4f4f4;
        }
        
        .container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        .nav-bar {
            background-color: #007bff;
            padding: 10px;
            display: flex;
            justify-content: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .nav-bar a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            padding: 8px 12px;
            background-color: #00509e;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .nav-bar a:hover {
            background-color: #003d7a;
        }
        .content {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .section {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .section h2 {
            color: #003366;
        }
        .footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px 20px;
            margin-top: auto;
        }
        
        @media (max-width: 600px) {
            .nav-bar {
                flex-direction: column;
                align-items: center;
            }
            .nav-bar a {
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <nav class="nav-bar">
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </nav>
        <header class="header">
            <h1>HMS Garage Management System</h1>
        </header>
        <main class="content">
            <h2>Welcome to the Garage Management System</h2>
           
            <section class="section">
                <h2>About Us</h2>
                <p>We are dedicated to providing top-notch garage services, including vehicle repairs, maintenance, and more. Our team of skilled professionals is committed to delivering high-quality workmanship and exceptional customer service.</p>
            </section>
            <section class="section">
                <h2>Contact Us</h2>
                <p>If you have any questions or need assistance, please reach out to us:</p>
                <p>Email: support@HMSgaragemanagement.com</p>
                <p>Phone: +123-456-7890</p>
            </section>
            <section class="section">
                <h2>What We Do</h2>
                <p>Our garage offers a wide range of services including:</p>
                <ul>
                    <li>Routine vehicle maintenance</li>
                    <li>Engine diagnostics and repair</li>
                    <li>Brake services</li>
                    <li>Transmission repairs</li>
                    <li>Custom modifications and upgrades</li>
                </ul>
            </section>
        </main>
        <footer class="footer">
            <p>&copy; 2024 HMS Garage Management System. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>