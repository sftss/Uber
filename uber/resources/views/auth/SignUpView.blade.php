<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription réussie</title>
</head>

<body>
    <h1>Bonjour, bienvenue sur notre site !</h1>
    <p>Merci de vous être inscrit. Nous sommes heureux de vous compter parmi nos membres.</p>
    <p>Votre code de vérification est : <strong>{{ $code_verif }}</strong></p>
</body>

</html>
<script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
<df-messenger style="z-index: 999;" intent="WELCOME" chat-title="HelperBot" agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745"
    language-code="fr"></df-messenger>
