<!-- app/view/user/signin.php -->
<!DOCTYPE html>
<html>
<head>
    <title>SignIn Colab</title>
    <style>
        .container {
            width: 500px;
            margin: auto;
            background: #f1f1f1;
            padding: 20px;
            margin-top: 50px;
        }
        input {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 10px;
        }
        button {
            padding: 10px 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <form method="post" action="">
        <input type="email" name="email" placeholder="Enter Email" required />
        <input type="password" name="password" placeholder="Enter Password" required />
        <button type="submit" name="btn">Login</button>
    </form>
</div>
</body>
</html>
