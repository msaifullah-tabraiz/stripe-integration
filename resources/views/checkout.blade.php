@extends('layouts.master')

@section('content')



<!-- Display a payment form -->
<form id="payment-form">
    <!-- display: none;  -->
    <div id="order-details" style="display: flex; justify-content: space-between;">
        <div id="order-details-id">9be033c8-1ae9-4232-a6d9-ab2e35940b43</div>
        <div id="order-details-amount">USD 325</div>
    </div>
    <div id="payment-element">
        <!--Stripe.js injects the Payment Element-->
    </div>
    <button id="submit">
        <div class="spinner hidden" id="spinner"></div>
        <span id="button-text">Pay now</span>
    </button>
    <div id="payment-message" class="hidden"></div>
</form>
@endsection