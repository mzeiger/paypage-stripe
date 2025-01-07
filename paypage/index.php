<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Page</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="container">
        <div class="card_box">
            <h1>Stripe Purchase Form</h1>
            <form action="./charge.php" method="post" id="payment-form">

                <div class="purchase">
                    <input type="text" name="product" id="product" size="50%" placeholder="Product" required />

                    <input type="text" name="unit_price" id="unit_price" size="20%" placeholder="Unit Price" required/>

                    <input type="number" name="quantity" id="quantity" placeholder="Quantity"required/>
                </div>
                <div>
                    <input type="text" name="first_name" placeholder="First Name" class="card_input" required />
                </div>
                <div>
                    <input type="text" name="last_name" placeholder="Last Name" class="card_input" required />
                </div>
                <div>
                    <input type="text" name="address" placeholder="Street Address" class="card_input" required />
                </div>
                <div>
                    <input type="text" name="city" placeholder="City" style="width: 75%;" class="card_input" required />
                    <input type="text" name="state" placeholder="State" style="width: 22%;" class="card_input"
                        required />
                    <!-- <input type="text" name="postal_code" placeholder="Zip Code" style="width: 15%;" class="card_input" required/> -->
                </div>
                <div>
                    <input type="email" name="email" placeholder="Email Address" class="card_input" required />
                </div>
                <div>
                    <input type="tel" name="phone" placeholder="Phone number in form of xxx-xxx-xxxx" class="card_input"
                        required pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" />
                </div>
                <div id="card-number-element" class="card_input">

                </div>
                <div id="card-expiry-element" class="card_input">

                </div>
                <div id="card-cvc-element" class="card_input">

                </div>
                <div id="card-zip-element" class="card_input">

                </div>
                <button id="card-button">Submit Payment</button>
            </form>
        </div>
    </div>

    <div class="card_box2">
        <div id="card-result">
            <!-- errors, if any, will be shown here -->
        </div>
        <div id="card-errors" role="alert">
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script src="./js/charge.js"></script>
</body>

</html>