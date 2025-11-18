<?php
// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    // Secure session settings - only set if session not started yet
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0); // Set to 1 when using HTTPS in production
    ini_set('session.use_strict_mode', 1);
    session_start();
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f5f6fa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .sheet {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 2rem 2.5rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        .container h2 {
            margin-bottom: 1.5rem;
            color: #2d3436;
            font-size: 1.8rem;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            text-align: left;
        }

        label {
            font-weight: 600;
            color: #2d3436;
            margin-bottom: 0.3rem;
            display: block;
        }

        input {
            width: 100%;
            padding: 0.7rem;
            border: 1px solid #dfe6e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input:focus {
            border-color: #0984e3;
            box-shadow: 0 0 5px rgba(9, 132, 227, 0.4);
            outline: none;
        }

        button {
            background: #0984e3;
            color: #fff;
            padding: 0.8rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s ease;
            font-weight: 600;
        }

        button:hover {
            background: #74b9ff;
        }

        p {
            margin-top: 1.2rem;
            color: #636e72;
            font-size: 0.95rem;
        }

        a {
            color: #0984e3;
            text-decoration: none;
            font-weight: 600;
        }

        a:hover {
            text-decoration: underline;
        }

        .password-requirements {
            font-size: 0.85rem;
            color: #636e72;
            margin-top: 0.5rem;
            padding: 0.5rem;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 3px solid #0984e3;
        }

        .password-requirements ul {
            margin: 0.3rem 0 0 1.2rem;
            padding: 0;
        }

        .password-requirements li {
            margin: 0.2rem 0;
        }
    </style>
</head>
<body>
    <main class="sheet">
        <div class="container">
            <h2>Create an Account</h2>
            <div>
                <form action="create.php" method="POST">
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    
                    <label for="id">Student ID:</label>
                    <input type="text" id="id" name="id" required maxlength="7" pattern="[0-9]{7}" title="7 digits only" autocomplete="username">

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required maxlength="100" autocomplete="email">

                    <label for="fname">First name:</label>
                    <input type="text" id="fname" name="fname" required maxlength="50" autocomplete="given-name">

                    <label for="lname">Last name:</label>
                    <input type="text" id="lname" name="lname" required maxlength="50" autocomplete="family-name">

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required minlength="8" maxlength="20" autocomplete="new-password">
                    
                    <div class="password-requirements">
                        <strong>Password must contain:</strong>
                        <ul>
                            <li>8-20 characters</li>
                            <li>One uppercase letter</li>
                            <li>One lowercase letter</li>
                            <li>One number</li>
                            <li>One special character</li>
                        </ul>
                    </div>

                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="8" maxlength="20" autocomplete="new-password">

                    <button type="submit">Create Account</button>
                </form>
            </div>
            <p>Already have an account? <a href="../index.php?page=login">Login here</a></p>
        </div>
    </main>
</body>
</html>