<?php
namespace App\Http\Controllers;
use Throwable;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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
                'amount' => ($request->amountInCents),
                'currency' => 'mxn',
                'description'=>"Pago por concepto de renta de departamento: ".($request->txtNombre),
                'receipt_email' => $request->stripeEmail,
            ));
            return redirect('/')->with('message_exito','Pago exitoso. Se envió el informe del pago al correo proporcionado.');
        } catch (\Exception $ex) {
            //manejando las posbiles excepciones
            $error='Ocurrió un error, por favor verifique sus datos.';
            //saldo insuficiente
            //dd($ex);
            try {
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
            } catch (\Throwable $th) {
                //error de numero
                if($ex->getMessage()=="A non well formed numeric value encountered"){
                    $error='Verifique bien la cantidad por favor';
                }else{
                    $error='Ocurrió un error, por favor verifique sus datos y reintente.';
                }
            }

            
            return redirect('/')->with('message_error',$error);
        }
    }
}