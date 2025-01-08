@extends('layouts.service-facturation-header')

<link rel="stylesheet" href="{{ URL::asset('assets/style/servicefacturation.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/style/app.css') }}" /> 


<body>
    <div class="form-container">
        <h1>Entrez un numéro de chauffeur</h1>
        <form action="#" method="get" class="form-chauffeur">
            <input type="text" name="chauffeur-number" id="chauffeur-number" placeholder="Numéro de chauffeur" class="input-text" required>
            <button type="submit" id="button-submit" class="button-submit">Confirmer</button>
        </form>
    </div>
</body>
<script>
document.getElementById('button-submit').addEventListener('click', function () {
    event.preventDefault(); // Empêche le formulaire de se soumettre de manière traditionnelle

var chauffeurNumber = document.getElementById('chauffeur-number').value; // Récupère la valeur du numéro de chauffeur

if (chauffeurNumber) {
    // Redirige vers la route en incluant l'ID du chauffeur dans l'URL
    window.location.href = "{{ url('service-facturation-courses') }}/" + chauffeurNumber;
} else {
    alert('Veuillez entrer un numéro de chauffeur.');
}
     
});
</script>