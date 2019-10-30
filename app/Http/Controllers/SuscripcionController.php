<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
class SuscripcionController extends Controller
{
public function pago(Request $request)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
        $customer = Customer::create(array(
                'email' => $request->stripeEmail,
                'source' => $request->stripeToken
            ));
        $charge = Charge::create(array(
                'customer' => $customer->id,
                'amount' => ($request->valor)*100,
                'currency' => 'mxn',
                'description'=>"Pago por concepto de renta de departamento",
                'receipt_email' => 'hectorcrzprz@gmail.com',
            ));
            return redirect('/')->with('message_exito','Su pago ha sido procesado con exito');
        } catch (\Exception $ex) {
            //manejando las posbiles excepciones
            $error='Ocurrió un error, por favor verifique sus datos.';
            //saldo insuficiente
            if($ex->getDeclineCode()=="insufficient_funds"){
                $error="Esta tarjeta no cuenta con saldo suficiente.";
            }else if($ex->getDeclineCode()=="card_declined"){
                $error="Esta tarjeta ha sido rechazada.";
            }else if($ex->getDeclineCode()=="lost_card"){
                $error="Esta tarjeta tiene reporte de extravío.";
            }else if($ex->getDeclineCode()=="stolen_card"){
                $error="Esta tarjeta tiene reporte de robo.";
            }else if($ex->getDeclineCode()=="expired_card"){
                $error="Esta tarjeta ha expirado.";
            }else if($ex->getDeclineCode()=="incorrect_cvc"){
                $error="Por favor verifique su código cvc.";
            }else if($ex->getDeclineCode()=="processing_error"){
                $error="Ocurrió un error, por favor reintente.";
            }else if($ex->getDeclineCode()=="incorrect_number"){
                $error="Por favor verifique número de tarjeta.";
            }
            return redirect('/')->with('message_error',$error);
        }
    }
}