<?php
namespace App\Http\Controllers;

use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class PayPalController extends Controller
{
    public function createPayment()
{
    $cart = session()->get('cart', []);
    $totalAmount = 0;

    foreach ($cart as $item) {
        $totalAmount += $item['total'];  // Utiliser le montant total de chaque élément
    }
    
    $paypal = new PayPalClient;
            $paypal->setApiCredentials(config('paypal'));
            $token = $paypal->getAccessToken();
            $paypal->setAccessToken($token);

            // Utilisation du montant total calculé
            $response = $paypal->createOrder([
                "intent" => "CAPTURE",
                "purchase_units" => [
                    0 => [
                        "amount" => [
                            "currency_code" => "EUR", 
                            "value" => $totalAmount // Formatage correct sans séparateur de milliers
                        ]
                    ]
                ],
                "application_context" => [
                    "cancel_url" => route('paypal.cancel'),
                    "return_url" => route('paypal.success')
                ]
            ]);






            // Redirection vers PayPal si la commande est créée avec succès
            if (isset($response['id']) && $response['status'] == "CREATED") {
                foreach ($response['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        return redirect()->away($link['href']);
                    }
                }
            }

            // Redirection en cas d'erreur
            return redirect()->route('paypal.cancel')->with('error', 'Une erreur est survenue.');
}

public function handleSuccess(Request $request)
        {
            $paypal = new PayPalClient;
            $paypal->setApiCredentials(config('paypal'));
            $token = $paypal->getAccessToken();
            $paypal->setAccessToken($token);

            $response = $paypal->capturePaymentOrder($request['token']);

            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                return redirect()->route('home-up')->with('success', 'Paiement réussi.');
            }

            return redirect()->route('home-up')->with('error', 'Le paiement a échoué.');
        }

        public function handleCancel()
        {
            return redirect()->route('home-up')->with('error', 'Le paiement a été annulé.');
        }
}
