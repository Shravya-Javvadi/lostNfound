<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - LostNFound</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa; /* Light background color */
            color: #343a40; /* Dark gray text color */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background-color: #343a40; /* Dark background */
            color: #ffffff;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }
        header .logo {
            height: 50px;
            background: none;
        }
        header h1 {
            flex: 1;
            text-align: center;
            margin: 0;
        }
        .profile-menu {
            display: flex;
            align-items: center;
        }
        .logout-button {
            background-color: #ffc107;
            border: none;
            color: #343a40;
            font-size: 1em;
            cursor: pointer;
            padding: 10px 20px;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s;
        }
        .logout-button:hover {
            background-color: #e0a800;
        }
        .container {
            display: flex;
            flex-direction: row;
            flex: 1;
            padding: 20px;
        }
        .sidebar {
            background-color: #495057;
            color: #ffffff;
            width: 250px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
            position: fixed; /* Fixed position to pin it to the left */
            top: 80px; /* Adjust based on header height */
            left: 0;
            height: calc(100% - 80px); /* Full height minus header height */
        }
        .sidebar h2 {
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }
        .sidebar button {
            display: block;
            color: #ffffff;
            text-decoration: none;
            padding: 12px;
            border: none;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: background-color 0.3s, transform 0.2s;
            width: 100%;
            text-align: left;
            background-color: #6c757d;
            border: 1px solid #495057;
        }
        .sidebar button:hover {
            background-color: #5a6268;
            transform: scale(1.02);
        }
        .sidebar button.menu-item {
            background-color: #007bff;
        }
        .sidebar button.menu-item:hover {
            background-color: #0056b3;
        }
        .sidebar button.item-status {
            background-color: #28a745;
        }
        .sidebar button.item-status:hover {
            background-color: #1e7e34;
        }
        .sidebar button.history {
            background-color: #ffc107;
        }
        .sidebar button.history:hover {
            background-color: #e0a800;
        }
        .sections-container {
            display: flex;
            justify-content: center; /* Center the sections container horizontally */
            align-items: center; /* Center the sections container vertically */
            flex: 1;
            padding-left: 270px; /* Space for the fixed sidebar */
        }
        .sections {
            display: flex;
            flex-direction: row; /* Row layout */
            justify-content: center; /* Center the sections horizontally */
            align-items: center; /* Center the sections vertically */
            gap: 20px;
            max-width: 100%; /* Limit the width of the sections container */
            width: 100%;
        }
        .section {
            text-align: center;
            width: 300px; /* Set a fixed width for the sections */
            height: 400px; /* Set a fixed height for the sections */
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s, transform 0.2s;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center content vertically */
        }
        .section:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            transform: scale(1.02);
        }
        .section img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .section h3 {
            margin-top: 10px;
            font-size: 1.5em;
            color: #343a40;
        }
        .section p {
            font-size: 0.9em;
            color: #6c757d;
            margin-top: 10px;
        }
        .section button {
            display: block;
            color: #007bff;
            text-decoration: none;
            padding: 12px;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s, transform 0.2s;
            background-color: #f4f4f4;
            width: 100%;
        }
        .section button:hover {
            background-color: #e0e0e0;
            transform: scale(1.02);
        }
        .footer {
            background-color: #343a40;
            color: #ffffff;
            text-align: center;
            padding: 10px 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        .footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php">
            <img src="logo.gif" alt="Logo" class="logo"> <!-- Ensure your logo.gif has a transparent background -->
        </a>
        <h1>LostNFound</h1>
        <div class="profile-menu">
            <button onclick="window.location.href='logout.php'" class="logout-button">Logout</button>
        </div>
    </header>
    <div class="container">
        <aside class="sidebar">
            <h2>Menu</h2>
            <button onclick="window.location.href='item_status.php'" class="item-status">Item Status</button>
            <button onclick="window.location.href='history.php'" class="history">History</button>
            <button onclick="window.location.href='change_password.php'" class="item-status">Change Password</button>
        </aside>
        <div class="sections-container">
            <main class="sections">
                <div class="section">
                    <img src="lstim.jpg" alt="Post Lost Item">
                    <h3>Post Lost Item</h3>
                    <p>Submit details of a lost item.</p>
                    <button onclick="window.location.href='post_lost_item.php'">Post Lost Item</button>
                </div>
                <div class="section">
                    <img src="fndim.jpeg" alt="Post Found Item">
                    <h3>Post Found Item</h3>
                    <p>Submit details of a found item.</p>
                    <button onclick="window.location.href='post_found_item.php'">Post Found Item</button>
                </div>
                <div class="section">
                    <img src="lf1.jpg" alt="View Items">
                    <h3>View Items</h3>
                    <p>View all lost and found items.</p>
                    <button onclick="window.location.href='search_items.php'">View Items</button>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zyn23gA5Zt8DJTUMn9j7bD3ez9z1Z3+3Ebg9p6fL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.6/dist/umd/popper.min.js" integrity="sha384-R7g5vK6D5znh2vDsgG9u9IibVuPv2F6ENpeW8U58PjQQU8z+YAp0MR2aOfjt5TZn" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-5dLw1tP1LgPY8A8I5W9RRZlf0mOOH+HG04xQ0nBGT/fpHjpH7vCzklqMeW6Az2Ic" crossorigin="anonymous"></script>
</body>
</html>