<?php

require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Invalid request.");
}

/* Get form data */
$full_name = isset($_POST["full_name"]) ? trim($_POST["full_name"]) : "";
$phone     = isset($_POST["phone"]) ? trim($_POST["phone"]) : "";
$product   = isset($_POST["product"]) ? trim($_POST["product"]) : "";
$quantity  = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 0;
$address   = isset($_POST["address"]) ? trim($_POST["address"]) : "";

/* Check fields */
if (
    empty($full_name) ||
    empty($phone) ||
    empty($product) ||
    $quantity < 1 ||
    empty($address)
) {
    die("Please complete all fields.");
}

/* Save order in database */

$sql = "INSERT INTO orders
        (full_name, phone, product, quantity, address)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);

$stmt->execute(array(
    $full_name,
    $phone,
    $product,
    $quantity,
    $address
));

$order_id = $pdo->lastInsertId();


/* ==========================
   SEND EMAIL
   ========================== */

$my_email = "dsdictators0@gmail.com";

$subject = "New Order #" . $order_id;

$message =
"NEW ORDER\n\n" .
"Order number: #" . $order_id . "\n\n" .
"Customer: " . $full_name . "\n" .
"Phone: " . $phone . "\n" .
"Product: " . $product . "\n" .
"Quantity: " . $quantity . "\n" .
"Address: " . $address . "\n";

$headers = "From: website@localhost\r\n";
$headers .= "Reply-To: website@localhost\r\n";

mail(
    $my_email,
    $subject,
    $message,
    $headers
);


/* ==========================
   CONFIRMATION PAGE
   ========================== */

?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <title>Order Confirmed</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            text-align: center;
            padding: 80px;
        }

        .box {
            background: white;
            max-width: 500px;
            margin: auto;
            padding: 40px;
            border-radius: 15px;
        }

        h1 {
            color: #111;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: #111;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

    </style>

</head>

<body>

    <div class="box">

        <h1>Order Confirmed ✓</h1>

        <p>
            Thank you
            <?php echo htmlspecialchars($full_name); ?>.
        </p>

        <p>
            Your order has been received.
        </p>

        <p>
            Order number:
            <strong>
                #<?php echo $order_id; ?>
            </strong>
        </p>

        <a href="index.html">
            Back to Store
        </a>

    </div>

</body>

</html>