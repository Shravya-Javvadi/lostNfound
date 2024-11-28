<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Lost and Found</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        h1, h2 {
            color: white;
        }
        body {
            position: relative;
            height: 100vh;
            margin: 0;
            padding: 0;
            background-color: rgba(0, 0, 0, 0.5); /* Black background with reduced opacity */
        }
        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('bg1.jpg'); /* Replace with your image path */
            background-size: cover;
            background-position: right center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            opacity: 0.6; /* Adjust this value to decrease opacity */
            z-index: -1;
        }
        .container {
            max-width: 100%;
            margin: auto;
            padding: 0 15px;
            position: relative;
            z-index: 1;
        }
        header {
            background-color: rgba(0, 0, 0, 0.5); /* Increased transparency */
            padding: 10px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: fadeIn 2s;
            position: relative;
            z-index: 2;
        }
        header img {
            height: 50px;
            margin-right: 10px;
            opacity: 0.8; /* Make the logo transparent */
            animation: fadeInLogo 2s;
        }
        header h1 {
            font-size: 1.5rem;
            margin: 0;
            text-align: center;
            flex: 1;
            color: white; /* White text for contrast */
            animation: fadeIn 2s;
        }
        header nav {
            margin-left: auto;
        }
        header nav a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-size: 1rem;
        }
        .about-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 80vh;
            color: white; /* White text for contrast */
        }
        .about-box {
            flex: 1;
            max-width: 800px;
            padding: 30px;
            background-color: rgba(0, 0, 0, 0.5); /* Increased transparency */
            border-radius: 10px;
            animation: slideUp 1s;
        }
        footer {
            background-color: rgba(52, 58, 64, 0.8); /* Increased transparency */
            color: #ffffff;
            padding: 10px 0;
            text-align: center;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        @keyframes fadeInLogo {
            from {
                opacity: 0;
                transform: rotate(-360deg);
            }
            to {
                opacity: 0.8;
                transform: rotate(0);
            }
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="background-image"></div>

    <header>
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="logo.gif" alt="Logo"> <!-- Replace with the path to your logo -->
                <h1>Lost'N'Found</h1>
            </div>
            <nav>
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
            </nav>
        </div>
    </header>

    <div class="about-container container">
        <div class="about-box">
            <h2>About Lost'N'Found</h2>
            <p>
                Welcome to the Lost'N'Found portal of Shri Vishnu Engineering College for Women.
                This platform is designed to help students and staff report and find lost items on campus.
            </p>
            <p>
                Our goal is to make the process of recovering lost items as efficient and straightforward as possible.
                By using this portal, you can quickly post information about lost items or browse through items that have been found by others.
            </p>
            <p>
                We hope this initiative will foster a sense of community and help everyone in our college stay connected and supportive of one another.
            </p>
        </div>
    </div>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>