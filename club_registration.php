<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Registration</title>
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    form {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 100%;
        width: 400px;
        box-sizing: border-box;
    }

    h2 {
        color: #333;
    }

    label {
        display: block;
        margin: 10px 0;
        color: #333;
    }

    input,
    textarea {
        width: calc(100% - 20px);
        padding: 10px;
        margin: 5px 0 15px 0;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    input[type="radio"] {
        margin-right: 5px;
    }

    input[type="submit"],
    input[type="reset"] {
        background-color: #4caf50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    input[type="submit"]:hover,
    input[type="reset"]:hover {
        background-color: #45a049;
    }

    div {
        margin-top: 20px;
        text-align: center;
    }

    a {
        color: #333;
        text-decoration: none;
        margin: 0 10px;
        font-weight: bold;
    }

    a:hover {
        color: #4caf50;
    }
</style>
</head>
<body>
    <form action="register_process.php" method="post">
        <h2>User Registration</h2>
        <label for="emp_id">Student ID:</label>
        <input type="text" name="emp_id" id="emp_id" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <label for="employee_name">Student Name:</label>
        <input type="text" name="employee_name" id="employee_name" required>

        <label for="department">Department:</label>
        <input type="text" name="department" id="department" required>

        <label for="mobile_no">Mobile Number (10 digits, starting with 9,8,7,6,5):</label>
        <input type="tel" name="mobile_no" id="mobile_no" pattern="[56789]\d{9}" required>

        <label for="email_id">Email:</label>
        <input type="email" name="email_id" id="email_id" required>

        <label for="salary">Club or Society:</label>
        <input type="text" name="salary" id="salary" required>

        <label for="dob">Date of Birth:</label>
        <input type="date" name="dob" id="dob" required>

        <label for="gender">Gender:</label>
        <input type="radio" name="gender" value="male" id="male" required>
        <label for="male">Male</label>
        <input type="radio" name="gender" value="female" id="female" required>
        <label for="female">Female</label>
        <input type="radio" name="gender" value="other" id="other" required>
        <label for="other">Other</label>

        <label for="address">Hobbies:</label>
        <textarea name="address" id="address" rows="4" required></textarea>

        <input type="submit" name="submit" value="Submit">
        <input type="reset" value="Clear">
    </form>
    <div>
        <a href="login.php">Login</a>
        <a href="aap.html">Home</a>
    </div>
</body>
</html>
