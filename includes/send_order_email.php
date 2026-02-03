<?php
include __DIR__ . "/../vendor/autoload.php";
include "mailtrap_config.php";

if (empty($smtpUser) || empty($smtpPass)) {
    return;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$customer_name = trim($user['user_firstname'] . ' ' . $user['user_lastname']);
if (empty($customer_name)) {
    $customer_name = $user['user_name'];
}
$customer_email = $user['user_email'];

$currencySymbol = isset($mailCurrencySymbol) ? $mailCurrencySymbol : '₱';
$currencyCode = isset($mailCurrencyCode) ? $mailCurrencyCode : 'PHP';
$order_items_html = '';
$order_items_text = '';
foreach ($_SESSION['last_order_items'] as $product_id => $item) {
    $subtotal = $item['price'] * $item['quantity'];
    $order_items_html .= "<tr><td>{$item['name']}</td><td>{$item['quantity']}</td><td>{$currencySymbol}" . number_format($item['price'], 2) . "</td><td>{$currencySymbol}" . number_format($subtotal, 2) . "</td></tr>";
    $order_items_text .= "{$item['name']} x {$item['quantity']} @ {$currencySymbol}" . number_format($item['price'], 2) . " = {$currencySymbol}" . number_format($subtotal, 2) . "\n";
}

$total = $_SESSION['last_order_total'];
$order_id = $_SESSION['last_order_id'];
$order_date = date('Y-m-d H:i:s');

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = $smtpHost;
    $mail->SMTPAuth = true;
    $mail->Username = $smtpUser;
    $mail->Password = $smtpPass;
    $mail->Port = $smtpPort;
    if ($smtpPort == 587) {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    } elseif ($smtpPort == 465) {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    }

    $mail->setFrom('noreply@example.com', 'CMS Store');
    $mail->addAddress($customer_email, $customer_name);
    $mail->Subject = "Order Confirmation #$order_id";

    $mail->AltBody = "Order Confirmation #$order_id\nDate: $order_date\n\nCustomer: $customer_name\nEmail: $customer_email\n\nOrder Details:\n$order_items_text\nTotal: {$currencySymbol}" . number_format($total, 2) . " ($currencyCode)\n\nThank you for your order!";

    $mail->Body = "<!doctype html><html><head><meta charset='UTF-8'></head><body style='font-family: sans-serif;'>" .
        "<div style='max-width: 600px; margin: auto;'>" .
        "<h1 style='font-size: 18px; font-weight: bold; margin-top: 20px'>Order Confirmation #$order_id</h1>" .
        "<p>Date: $order_date</p>" .
        "<h2 style='font-size: 16px;'>Customer Information</h2>" .
        "<p><strong>Name:</strong> $customer_name<br><strong>Email:</strong> $customer_email</p>" .
        "<h2 style='font-size: 16px;'>Order Information</h2>" .
        "<table style='border-collapse: collapse; width: 100%;'><tr style='background: #f5f5f5;'>" .
        "<th style='padding: 8px; text-align: left;'>Product</th><th style='padding: 8px;'>Qty</th><th style='padding: 8px;'>Price</th><th style='padding: 8px;'>Subtotal</th></tr>" .
        $order_items_html .
        "</table>" .
        "<p style='font-size: 18px; font-weight: bold; margin-top: 20px;'>Total: {$currencySymbol}" . number_format($total, 2) . " ($currencyCode)</p>" .
        "<p>Thank you for your order!</p></div></body></html>";
    $mail->isHTML(true);

    $mail->send();
} catch (Exception $e) {
    error_log("Mailtrap send failed: " . $mail->ErrorInfo);
}
?>
