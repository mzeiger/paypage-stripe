<?php

// If rebuilding app run "composer require vlucas/phpdotenv" (no quotes) from command line

require_once 'vendor/autoload.php';

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$secretKey = $_ENV['STRIPE_SECRET_TEST_KEY_FOR_OPEN_FOR_USE'];
$database = $_ENV['DBNAME'];
$dbUserName = $_ENV['DB_USER_NAME'];
$dbPassword = $_ENV['DB_PASSWORD'];
$dbCredentials = array($database, $dbUserName, $dbPassword);

\Stripe\Stripe::setApiKey($secretKey);

// Sanitize POST array

$POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
$first_name = $POST['first_name'];
$last_name = $POST['last_name'];
$email = $POST['email'];
$address = $POST['address'];
$city = $POST['city'];
$state = $POST['state'];
$phone = $POST['phone'];
$product = $POST['product'];
$quantity = $POST['quantity'];
$unitPrice = $POST['unit_price'];
$token = $POST['stripeToken'];

// for the address in the customer
$addressArray = array(
    "line1" => $address,
    "city" => $city,
    "state" => $state,
);

// Create customer in Stripe
$customer = \Stripe\Customer::create(array(
    "name" => $first_name . " " . $last_name,
    "address" => $addressArray,
    "email" => $email,
    "phone" => $phone,
    "source" => $token
));
// Charge Customer
$charge = \Stripe\Charge::create(array(
    "amount" => $unitPrice * $quantity * 100,
    "currency" => "usd",
    //"description" => "Intro to React Course",
    "description" => $product,
    "customer" => $customer->id
));

// print_r($charge->status);

if (insertIntoDatabase($customer, $charge, $POST, $dbCredentials)) {
    // $transactionAmount = $charge->amount / 100;
    // $formattedTransactionAmount = number_format($transactionAmount, 2, '.', ',');
    // header('Location: success.php?tid=' . $charge->id .
    //     '&product=' . $charge->description . "&price=" . $formattedTransactionAmount . "&name=" .
    //     $customer->name . "&address=" . $customer->address->city . "&postal_code=" . $postalCode . "&receipt_url=" . $charge->receipt_url);
    header("Location: " . $charge->receipt_url);
}
function insertIntoDatabase($customer, $charge, $POST, $dbCredentials)
{
    try {
        $dbConnectionsString = sprintf("mysql:host=localhost;dbname=%s", $dbCredentials[0]);
        $dbh = new PDO($dbConnectionsString, $dbCredentials[1], $dbCredentials[2]);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->beginTransaction();
        $sql = "insert into customers (id, charge_id, first_name, last_name, email, phone, address, city, state, zipcode) values (?,?,?,?,?,?,?,?,?,?)";
        $insetArray = array(
            $customer->id,
            $charge->id,
            $POST['first_name'],
            $POST['last_name'],
            $POST['email'],
            $POST['phone'],
            $customer->address->line1,
            $customer->address->city,
            $customer->address->state,
            $charge->billing_details->address->postal_code
            // $POST['postal_code']
        );
        $stmt = $dbh->prepare($sql);
        $stmt->execute($insetArray);
        $stmt = null;

        // TODO:: insert int the local "Transacts" table

        $sql = "insert into  transactions (id, customer_id, product, unit_price, quantity, currency)
         values (?, ?, ?, ?, ?, ?)";
        $stmt = $dbh->prepare($sql);
        $insetArray = array(
            $charge->id,
            $customer->id,
            $charge->description,
            $POST['unit_price'],
            $POST['quantity'],
            $charge->currency
      );
        $stmt->execute($insetArray);

        $dbh->commit();
        $dbh = null;
        return true;
    } catch (PDOException $e) {
        $dbh->rollBack();
        echo "Local database insertion failed: " . $e->getMessage();
        return false;
    }
}
