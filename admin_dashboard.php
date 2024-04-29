<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        li {
            width: 200px;
            margin: 0 auto;
        }

        a {
            text-decoration: none;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            background-color: #3498db;
            display: block;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #2980b9;
        }

        a:last-child {
            background-color: #e74c3c;
        }

        a:last-child:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <h1>Welcome, Admin!</h1>
    <ul>
        <li><a href="admin_dash.php">Check Pending Requests</a></li>
        <li><a href="add_event.php">Add Event</a></li>
        <li><a href="admin_list.php">List of all students</a></li>
    </ul><br>
    <a href="aap.html">Logout</a>
</body>
</html>
