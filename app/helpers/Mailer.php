<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

class Mailer {
    public static function sendOrderConfirmation($toEmail, $toName, $order) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USERNAME;
            $mail->Password   = MAIL_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($toEmail, $toName);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "Order #{$order->getId()} Confirmation";

            $itemsHtml = "";
            foreach ($order->getItems() as $item) {
                $itemsHtml .= "<li>{$item->getProductName()} (x{$item->getQuantity()}) - \${$item->getPrice()}</li>";
            }

            $mail->Body = "
                <h2>Thank you for your order, {$toName}!</h2>
                <p>Your order <strong>#{$order->getId()}</strong> has been placed successfully.</p>
                <p><strong>Order Details:</strong></p>
                <ul>$itemsHtml</ul>
                <p><strong>Total: </strong>\${$order->getTotalAmount()}</p>
                <br>
                <p>We will notify you once it ships.</p>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mail could not be sent. Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
