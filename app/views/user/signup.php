<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>

<h2>Sign Up</h2>
<form method="POST">

    <!-- First Name -->
    <div>
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" required>
    </div>

    <!-- Last Name -->
    <div>
        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" required>
    </div>

    <!-- Email -->
    <div>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
    </div>

    <!-- Mobile -->
    <div>
        <label for="mobile">Mobile</label>
        <input type="text" id="mobile" name="mobile" required>
    </div>

    <!-- Password -->
    <div>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
    </div>

    <!-- Submit Button -->
    <div>
        <button name="btn" type="submit">Sign Up</button>
    </div>

</form>

</body>
</html>