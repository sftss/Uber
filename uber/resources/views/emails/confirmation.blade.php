<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de votre email</title>
</head>
<body>
    <h1>Bonjour {{ $user->name }}</h1>
    <p>Merci de vous être inscrit ! Pour confirmer votre adresse email, veuillez cliquer sur le lien ci-dessous :</p>
    <a href="{{ url('/confirm-email/' . $confirmation_code) }}">Confirmer mon email</a>
</body>
</html>