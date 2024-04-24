// This is your test publishable API key.
const backendUrl = "http://127.0.0.1:8000";
const frontendUrl = "http://127.0.0.1:8001";
// const stripe = Stripe("pk_test_51P7AqdD9R2Z3NTqHGIP9elTMH5N24C41hWnayEBAy7IzScdw4YCPYyPUacnWoqk2t682DAcyq4tCqP69RAeJD5Sg00WVem1GqP");
let stripe = null;

const domain = "good";
const token = "Bearer 1|vygVRhoKkODdUfd71nf9HhO0QGfAMgAjME7MykRt83b30a90";
const data = {
    shop_order_id: 1,
    payment_gateway_id: 1,
};

let elements;

initialize();
checkStatus();

document
    .querySelector("#payment-form")
    .addEventListener("submit", handleSubmit);

// Fetches a payment intent and captures the client secret
async function initialize() {
    axios.defaults.baseURL = backendUrl;
    axios.defaults.withCredentials = true;
    axios.defaults.withXSRFToken = true;

    try {
        /* validate token */
        await axios.get(`/api/websites/${domain}/website-users/me`, {
            headers: {
                Accept: "application/json",
                Authorization: token,
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        });
        const response = await axios.post(
            `/api/websites/${domain}/stripe-payment-intents`,
            data,
            {
                headers: {
                    Accept: "application/json",
                    Authorization: token,
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            }
        );
        // console.log(response.data);
        const publicKey = response.data.public_key;
        const clientSecret = response.data.client_secret;
        stripe = Stripe(publicKey);
        elements = stripe.elements({ clientSecret });

        const paymentElementOptions = {
            layout: "tabs",
        };

        const paymentElement = elements.create(
            "payment",
            paymentElementOptions
        );
        paymentElement.mount("#payment-element");
    } catch (error) {}
    // .then((r)=>{
    //   /*  */

    // });

    // /create.php
}

async function handleSubmit(e) {
    e.preventDefault();
    setLoading(true);

    // return_url: "http://localhost:4242/checkout.html",
    const { error } = await stripe.confirmPayment({
        elements,
        confirmParams: {
            // Make sure to change this to your payment completion page
            return_url: `${frontendUrl}/checkout/completed`,
        },
    });

    // This point will only be reached if there is an immediate error when
    // confirming the payment. Otherwise, your customer will be redirected to
    // your `return_url`. For some payment methods like iDEAL, your customer will
    // be redirected to an intermediate site first to authorize the payment, then
    // redirected to the `return_url`.
    if (error.type === "card_error" || error.type === "validation_error") {
        showMessage(error.message);
    } else {
        showMessage("An unexpected error occurred.");
    }

    setLoading(false);
}

// Fetches the payment intent status after payment submission
async function checkStatus() {
    const clientSecret = new URLSearchParams(window.location.search).get(
        "payment_intent_client_secret"
    );

    if (!clientSecret) {
        return;
    }

    const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

    switch (paymentIntent.status) {
        case "succeeded":
            showMessage("Payment succeeded!");
            break;
        case "processing":
            showMessage("Your payment is processing.");
            break;
        case "requires_payment_method":
            showMessage("Your payment was not successful, please try again.");
            break;
        default:
            showMessage("Something went wrong.");
            break;
    }
}

// ------- UI helpers -------

function showMessage(messageText) {
    const messageContainer = document.querySelector("#payment-message");

    messageContainer.classList.remove("hidden");
    messageContainer.textContent = messageText;

    setTimeout(function () {
        messageContainer.classList.add("hidden");
        messageContainer.textContent = "";
    }, 4000);
}

// Show a spinner on payment submission
function setLoading(isLoading) {
    if (isLoading) {
        // Disable the button and show a spinner
        document.querySelector("#submit").disabled = true;
        document.querySelector("#spinner").classList.remove("hidden");
        document.querySelector("#button-text").classList.add("hidden");
    } else {
        document.querySelector("#submit").disabled = false;
        document.querySelector("#spinner").classList.add("hidden");
        document.querySelector("#button-text").classList.remove("hidden");
    }
}
