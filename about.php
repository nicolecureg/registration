<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="team.css">
    <title>MEMBERS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: pink;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .profiles {
            display: flex; /* Use flexbox */
            justify-content: space-around; /* Distribute space evenly between profiles */
            padding: 20px;
            margin: 20px;
            background-color: #fff;
            border-radius: 8px;
        }

        .profile {
            display: flex; /* Use flexbox */
            flex-direction: column; /* Arrange items vertically */
            align-items: center; /* Center items horizontally */
            text-align: center;
        }

        .profile img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 20px; /* Add margin to create space between image and text */
        }

        ul {
            list-style-type: none;
            padding: 0;
            text-align: left; /* Align text to the left */
        }

        ul li {
            margin-bottom: 10px;
        }

        .profile h2 {
            margin-bottom: 10px; /* Add space between the name and the list */
        }
    </style>
</head>
<body>
    <?php include("homepage.php"); ?>
    <header>
        <h1>MEMBERS</h1>
    </header>
    <div class="profiles">
        <div class="profile">
            <h2>Marvita Yadan</h2>
            <ul>
                <li>Age: 20</li>
                <li>Gender: Female</li>
                <li>Status: Single</li>
                <li>Birthday: November 17, 2003</li>
                <li>Address: Cansan Cabagan Isabela</li>
            </ul>
        </div>
        <div class="profile">
            <h2>Nicole Cureg</h2>
            <ul>
                <li>Age: 20</li>
                <li>Gender: Female</li>
                <li>Status: Single</li>
                <li>Birthday: November 17, 2003</li>
                <li>Address: Cansan Cabagan Isabela</li>
            </ul>
        </div>
        <div class="profile">
            <h2>Adrian Orbng</h2>
            <ul>
                <li>Age: 20</li>
                <li>Gender: Male</li>
                <li>Status: Single</li>
                <li>Birthday: November 17, 2003</li>
                <li>Address: Cansan Cabagan Isabela</li>
            </ul>
        </div>
    </div>
</body>
</html>