<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Simple PHP MVC</title>
</head>
<body>
<h1><?php echo $data['greeting']; ?></h1>
<p>Retrieved Email from Database: <strong><?php echo htmlspecialchars($data['email']); ?></strong></p>
<p style="color: green;"><?php echo $data['emailStatus']; ?></p>
</body>
</html>