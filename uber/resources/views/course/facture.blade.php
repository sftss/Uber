<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ csrf_token() }}" name="csrf-token">
    <title>{{ __('facture.invoice_title') }}</title>
</head>
<style>
    /* Global */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        color: #333;
    }

    .container {
        padding: 2rem;
    }

    .container-img-logo {
        margin: 0 auto;
        transform: translate(-33%, -20%);
    }

    #heading {
        display: flex;
    }

    #ADroite {
        margin: 0 75% 0 0;
        transform: translate(0, 100%);
    }

    #AGauche {
        margin: 0 auto 0 59%;
    }

    /* En-tête */
    .header {
        text-align: center;
        border-bottom: 2px solid #000;
        margin-bottom: 1rem;
    }

    .header h1 {
        margin: 0;
        color: #000;
    }

    .header p {
        margin: 0.5rem 0;
        font-size: 1rem;
    }

    .client-info,
    .course-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .section {
        width: 100%;
    }

    .section h3 {
        margin-bottom: 0.5rem;
        color: #333;
        border-bottom: 1px solid #ddd;
        padding-bottom: 0.3rem;
    }

    .section p {
        margin: 0.3rem 0;
    }

    /* Tableau des produits */
    table {
        width: 100%;
        border-spacing: 0;
        border-collapse: collapse;
        margin-bottom: 1.5rem;
    }

    table.products th {
        background-color: #000;
        color: #fff;
        padding: 0.75rem;
        text-align: left;
    }

    table.products tr.items:nth-child(odd) {
        background-color: #f1f5f9;
    }

    table.products tr.items:nth-child(even) {
        background-color: #fff;
    }

    table.products td {
        padding: 0.75rem;
        border: 1px solid #ddd;
    }

    /* Total et Footer */
    .footer {
        text-align: center;
        margin-top: 1rem;
        padding-top: 0.5rem;
        border-top: 2px solid #ddd;
        font-size: 0.9rem;
        color: #555;
    }

    .footer p {
        margin: 0.5rem 0;
    }

    .footer strong {
        color: #333;
    }

    #totalTxt {
        display: flex;
        gap: 5px;
        flex-direction: row-reverse;
        font-size: 1.5rem
    }
</style>

<body>
    <div class="container">
        <div class="header">
            <div class="container-img-logo">
                <img alt="Logo Uber" src="{{ public_path('assets/img/Uber-Logo.webp') }}" width="200">
            </div>
            <div id="heading">
                <h1 id="ADroite">{{ __('facture.invoice_title') }}</h1>
                <h1 id="AGauche">{{ __('facture.id_title') }} : {{ $id_course }}</h1>
            </div>
        </div>
        <div class="client-info">
            <div class="section">
                <h3>{{ __('facture.client_info') }}</h3>
                <p><strong>{{ __('facture.client_name') }}:</strong> {{ $client->prenom_cp }} {{ $client->nom_cp }}</p>
                <p><strong>Email:</strong> {{ $client->mail_client }}</p>
                <p><strong>{{ __('facture.clientTel') }}:</strong> +33 {{ $client->tel_client }}</p>
            </div>
            @if (isset($chauffeur))
                <div class="section">
                    <h3>{{ __('facture.driver_info') }}</h3>
                    <p><strong>{{ __('facture.driver_name') }}:</strong> {{ $chauffeur->nom_chauffeur }}
                        {{ $chauffeur->prenom_chauffeur }}</p>
                    <p><strong>Email:</strong> {{ $chauffeur->mail_chauffeur }}</p>
                    <p><strong>{{ __('facture.driverTel') }}:</strong> +33 {{ $chauffeur->tel_chauffeur }}</p>
                    <p><strong>{{ __('facture.nameEnt') }} :</strong> {{ $chauffeur->nom_entreprise }}</p>
                </div>
            @endif
        </div>
        <div class="course-info">
            <div class="section">
                <h3>{{ __('facture.course_info') }}</h3>
                <p><strong>{{ __('facture.departure_address') }}:</strong> {{ $lieu_depart->rue }}
                    {{ $lieu_depart->cp }} {{ $lieu_depart->ville }}</p>
                <p><strong>{{ __('facture.arrival_address') }}:</strong> {{ $lieu_arrivee->rue }}
                    {{ $lieu_arrivee->cp }} {{ $lieu_arrivee->ville }}</p>
                <p><strong>{{ __('facture.pickup_date') }}:</strong> {{ $date_prise_en_charge }}</p>
                <p><strong>{{ __('facture.duration') }}:</strong> {{ $duree_course }}</p>

            </div>
        </div>
        <table class="products">
            <thead>
                <tr>
                    <th>{{ __('facture.product') }}</th>
                    <th>{{ __('facture.unit_price') }}</th>
                    <th>{{ __('facture.vat') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr class="items">
                        <td>{{ $item['name'] }}</td>
                        <td>{{ number_format($item['price'], 2) }} €</td>
                        <td>{{ $item['tva'] == '0%' ? '' : $item['tva'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="footer">
            <p id="totalTxt">{{ __('facture.total_ht') }} : <strong>{{ number_format($totalHT, 2) }} €</strong></p>
            <p id="totalTxt">{{ __('facture.total_ttc') }} : <strong>{{ number_format($totalTTC, 2) }} €</strong></p>
            <p>{{ __('facture.thank_you') }} - © Uber</p>
        </div>
    </div>
</body>

</html>
