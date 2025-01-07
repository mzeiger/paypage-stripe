<?php
    if (!empty($_GET['tid'] && !empty($_GET['product']) && !empty($_GET['price']))) {
        $GET = filter_var_array($_GET, FILTER_SANITIZE_STRING);
        $tid = $GET['tid'];
        $product = $GET['product'];
        $price = $GET['price'];
        $name = $GET['name'];
        $address = $GET['address'];
        $address = $GET['address'];
        $postalCode = $GET['postal_code'];
        $receipt = $GET['receipt_url'];
        print_r($GET);
    } else {
        header('Location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
</head>

<body>
    <div class="container mt-4">
        <h2>Thank you for purchasing <?php echo $product; ?> </h2>
        <hr/>
        <p>Your transaction Id is <?php echo $tid; ?></p>
        <p>Your cost is <?php echo $price ?></p>
        <p>Check your email for more info</p>
        <p>Name: <?php echo $name ?> </p>
        <p>Address: <?php echo $address ?> </p>
        <p>Zip Code: <?php echo $postalCode ?></p>
        <p>Receipt: <?php echo $receipt ?></p>
        <p><button onclick="location.href='index.php'" class="btn mt-2">Go Back</button></p>


    </div>

</body>

</html>