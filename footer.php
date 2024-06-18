<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title></title>
    <style>
    body {
    font-family: Arial, sans-serif;
    margin: 20px;
}

h1, h2 {
    color: white;
}

footer {
    background-color: black;
    padding: 20px;
    text-align: center;
    margin-top: 20px;
    border-top: 1px solid #ccc;
}

footer h2 {
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color : white;
}

input[type="email"], textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

textarea {
    resize: vertical;
    height: 100px;
}

input[type="submit"] {
    background-color: white;
    color: black;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

.message {
    border: 1px solid #ccc;
    padding: 10px;
    margin: 10px 0;
    border-radius: 4px;
}

.message p {
    margin: 5px 0;
}

.message form {
    margin-top: 10px;
}


    </style>
</head>
<body>
<footer>
    <h2>Service Client</h2>
    <form action="admin/service_client.php" method="post">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>
        </div>
        <input type="submit" value="Soumettre">
    </form>
</footer>
</body>
</html>
