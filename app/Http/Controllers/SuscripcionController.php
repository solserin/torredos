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
            return redirect('/')->with('message_error','Cantidad minima');
            //return back()->withInput();
            dd($ex);
            return $ex->getMessage();
        }
    }
}