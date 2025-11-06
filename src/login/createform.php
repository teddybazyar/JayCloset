<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<style>

.container {
  background: #fff;
  padding: 2rem 2.5rem;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  width: 100%;
  max-width: 420px;
  text-align: center;
  align-items: center;
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

.sheet {
  margin: auto;
  padding: 0;
  align-items: center;
}
</style>
<body><main class="sheet">
    <div class="container">
        <h2>Create an Account</h2>
        <div><form action="login/create.php" method="POST">
            <label for="id">Student ID:</label>
            <input type="text" id="id" name="id" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="fname">First name:</label>
            <input type="fname" id="fname" name="fname" required>

            <label for="lname">Last name:</label>
            <input type="lname" id="lname" name="lname" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Create Account</button>
        </form></div>
        <p>Already have an account? <a href="index.php?page=login">Login here</a></p>
    </div>
</main></body>
</html>