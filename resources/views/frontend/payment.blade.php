<!doctype html>
<html lang="en">

<head>
    <title>Seat Booking Payment</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://js.stripe.com/v3/"></script>

    <style>
        body {
            background: #f7f9fc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        .card {
            max-width: 480px;
            margin: 3rem auto;
            box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, 0.1);
            border-radius: 1rem;
        }

        .btn-pay {
            background: #6772e5;
            color: #fff;
            font-weight: 600;
            font-size: 1.2rem;
            border-radius: 0.5rem;
            width: 100%;
            padding: 0.75rem;
            transition: background 0.3s ease;
        }

        .btn-pay:hover {
            background: #5469d4;
            color: #fff;
        }

        .price {
            font-size: 2rem;
            font-weight: 700;
            color: #1a1a1a;
        }

        .text-muted-small {
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <main>
        <div class="card">
            <div class="card-body p-4">
                <h2 class="card-title mb-3 text-center">Complete Your Payment</h2>

                <div class="mb-3">
                    <strong>Booking ID:</strong>
                    <p class="mb-1">{{ $booking->id }}</p>
                </div>

                <div class="mb-3">
                    <strong>Event:</strong>
                    <p class="mb-1">{{ $booking->event->name }}</p>
                    <p class="text-muted-small">
                        {{ \Carbon\Carbon::parse($booking->event_datetime)->format('F j, Y, g:i A') }}</p>
                </div>

                <div class="mb-3">
                    <strong>Seats:</strong>
                    <ul class="list-group list-group-flush">
                        @foreach ($booking->seats as $seat)
                            <li class="list-group-item px-0">{{ $seat->seat->name ?? 'Seat #' . $seat->seat_id }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="mb-4">
                    <span class="price">${{ number_format($booking->total_amount, 2) }}</span>
                </div>

                <button id="checkout-button" class="btn btn-pay">Pay Now</button>

                <div id="payment-message" class="mt-3 text-center text-danger" style="display:none;"></div>
            </div>
        </div>
    </main>

    <script>
        const stripe = Stripe("{{ config('services.stripe.key') }}");

        document.getElementById('checkout-button').addEventListener('click', () => {
            document.getElementById('checkout-button').disabled = true;
            fetch("{{ route('payment.page', ['booking' => $booking->id]) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(async (res) => {
                    if (!res.ok) {
                        const err = await res.json();
                        throw new Error(err.message || 'Failed to create checkout session');
                    }
                    return res.json();
                })
                .then(data => {
                    return stripe.redirectToCheckout({
                        sessionId: data.session_id
                    });
                })
                .then(result => {
                    if (result.error) {
                        const messageEl = document.getElementById('payment-message');
                        messageEl.textContent = result.error.message;
                        messageEl.style.display = 'block';
                        document.getElementById('checkout-button').disabled = false;
                    }
                })
                .catch(err => {
                    const messageEl = document.getElementById('payment-message');
                    messageEl.textContent = err.message;
                    messageEl.style.display = 'block';
                    document.getElementById('checkout-button').disabled = false;
                });
        });
    </script>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
</body>

</html>
