<div class="content">
        <h1>Compra de Prueba</h1>
        <h3>US$ 19.99</h3>
        @if(session()->has('message_error'))
            <div class="alert alert-danger disappear" >
                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                <strong>{{ session()->get('message_error') }}</strong>
            </div>
      @endif
      @if(session()->has('message_exito'))
      <div class="alert alert-danger disappear" >
          <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
          <strong>{{ session()->get('message_exito') }}</strong>
      </div>
@endif
        <form action="/pago" method="POST">
            <input type="text" id="valor" name="valor">
            {{ csrf_field() }}
            <script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="{{ config('services.stripe.key') }}"
                data-amount=""
                data-name="Departamentos Torre II"
                data-description="Módulo de pago"
                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                data-locale="es"
                data-currency="mxn"
                data-label="Pagar Reserva"
                >
            </script>
        </form>
    </div>