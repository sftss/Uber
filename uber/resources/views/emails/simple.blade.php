<!DOCTYPE html>
<html>

<head>
    <title>{{ $subject }}</title>
</head>

<body>
    <p>{{ $message }}</p> <!-- Affichage du message ici -->
</body>

</html>
<script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
<df-messenger style="z-index: 999;" intent="WELCOME" chat-title="HelperBot" agent-id="bf5ac27d-e2ba-43f5-96e1-5dfbc6ad7745"
    language-code="fr"></df-messenger>
