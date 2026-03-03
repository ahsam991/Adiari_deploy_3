<?php
/**
 * Email Helper
 * Handles sending emails using PHP mail() function or SMTP
 */

class Email {
    private static $config = null;
    
    /**
     * Load email configuration
     */
    private static function loadConfig() {
        if (self::$config === null) {
            $appConfig = require __DIR__ . '/../../config/app.php';
            self::$config = [
                'from_email' => $appConfig['business']['email'] ?? 'info@adiari.shop',
                'from_name' => $appConfig['business']['name'] ?? 'ADI ARI Fresh',
                'admin_email' => $appConfig['business']['email'] ?? 'info@adiari.shop',
            ];
        }
        return self::$config;
    }
    
    /**
     * Send an email
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $message Email body (HTML)
     * @param string|null $replyTo Reply-to email
     * @return bool Success status
     */
    public static function send($to, $subject, $message, $replyTo = null) {
        $config = self::loadConfig();
        
        // Set headers
        $headers = [];
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/html; charset=UTF-8";
        $headers[] = "From: {$config['from_name']} <{$config['from_email']}>";
        
        if ($replyTo) {
            $headers[] = "Reply-To: {$replyTo}";
        }
        
        // Send email using PHP mail() function
        try {
            $result = mail($to, $subject, $message, implode("\r\n", $headers));
            
            // Log email sending
            if ($result) {
                error_log("Email sent successfully to: {$to} - Subject: {$subject}");
            } else {
                error_log("Failed to send email to: {$to} - Subject: {$subject}");
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Email sending error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send order confirmation email to customer
     * @param array $order Order data
     * @param array $items Order items
     * @return bool
     */
    public static function sendOrderConfirmation($order, $items) {
        $config = self::loadConfig();
        
        $to = $order['shipping_email'];
        $subject = "Order Confirmation - Order #{$order['order_number']}";
        
        // Build email message
        $message = self::getOrderConfirmationTemplate($order, $items);
        
        return self::send($to, $subject, $message);
    }
    
    /**
     * Send new order notification to admin
     * @param array $order Order data
     * @param array $items Order items
     * @return bool
     */
    public static function sendNewOrderNotification($order, $items) {
        $config = self::loadConfig();
        
        $to = $config['admin_email'];
        $subject = "New Order Received - Order #{$order['order_number']}";
        
        // Build email message
        $message = self::getNewOrderNotificationTemplate($order, $items);
        
        return self::send($to, $subject, $message);
    }
    
    /**
     * Get order confirmation email template
     */
    private static function getOrderConfirmationTemplate($order, $items) {
        $orderNumber = htmlspecialchars($order['order_number']);
        $customerName = htmlspecialchars($order['shipping_first_name'] . ' ' . $order['shipping_last_name']);
        $totalAmount = number_format($order['total_amount']);
        
        $itemsHtml = '';
        foreach ($items as $item) {
            $name = htmlspecialchars($item['product_name']);
            $qty = (int)$item['quantity'];
            $price = number_format($item['unit_price']);
            $total = number_format($item['total_price']);
            
            $itemsHtml .= "
                <tr>
                    <td style='padding: 10px; border-bottom: 1px solid #eee;'>{$name}</td>
                    <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: center;'>{$qty}</td>
                    <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: right;'>¥{$price}</td>
                    <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: right;'>¥{$total}</td>
                </tr>
            ";
        }
        
        $taxAmountStr = '';
        if (isset($order['tax_amount']) && $order['tax_amount'] > 0) {
            $taxAmount = number_format($order['tax_amount']);
            $taxAmountStr = "
                            <tr>
                                <td colspan='3' style='padding: 10px 15px; text-align: right;'>Tax Amount:</td>
                                <td style='padding: 10px 15px; text-align: right;'>¥{$taxAmount}</td>
                            </tr>
            ";
        }
        
        $address = htmlspecialchars($order['shipping_address_line1']);
        if ($order['shipping_address_line2']) {
            $address .= ', ' . htmlspecialchars($order['shipping_address_line2']);
        }
        $address .= '<br>' . htmlspecialchars($order['shipping_city']);
        if ($order['shipping_state']) {
            $address .= ', ' . htmlspecialchars($order['shipping_state']);
        }
        if ($order['shipping_postal_code']) {
            $address .= ' ' . htmlspecialchars($order['shipping_postal_code']);
        }
        $address .= '<br>' . htmlspecialchars($order['shipping_country']);
        
        $phone = htmlspecialchars($order['shipping_phone']);
        $paymentMethod = ucfirst($order['payment_method']);
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Order Confirmation</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: #4CAF50; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;'>
                <h1 style='margin: 0;'>Order Confirmation</h1>
            </div>
            
            <div style='background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 0 0 5px 5px;'>
                <p>Dear {$customerName},</p>
                <p>Thank you for your order! We're happy to confirm that we have received your order.</p>
                
                <div style='background: white; padding: 15px; margin: 20px 0; border-radius: 5px;'>
                    <h2 style='margin-top: 0; color: #4CAF50;'>Order Details</h2>
                    <p><strong>Order Number:</strong> {$orderNumber}</p>
                    <p><strong>Payment Method:</strong> {$paymentMethod}</p>
                </div>
                
                <div style='background: white; padding: 15px; margin: 20px 0; border-radius: 5px;'>
                    <h3 style='margin-top: 0;'>Items Ordered</h3>
                    <table style='width: 100%; border-collapse: collapse;'>
                        <thead>
                            <tr style='background: #f0f0f0;'>
                                <th style='padding: 10px; text-align: left;'>Product</th>
                                <th style='padding: 10px; text-align: center;'>Qty</th>
                                <th style='padding: 10px; text-align: right;'>Price</th>
                                <th style='padding: 10px; text-align: right;'>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$itemsHtml}
                        </tbody>
                        <tfoot>
                            {$taxAmountStr}
                            <tr>
                                <td colspan='3' style='padding: 15px; text-align: right; font-weight: bold;'>Total Amount:</td>
                                <td style='padding: 15px; text-align: right; font-weight: bold; color: #4CAF50; font-size: 1.2em;'>¥{$totalAmount}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div style='background: white; padding: 15px; margin: 20px 0; border-radius: 5px;'>
                    <h3 style='margin-top: 0;'>Shipping Address</h3>
                    <p>{$address}</p>
                    <p><strong>Phone:</strong> {$phone}</p>
                </div>
                
                <div style='background: #fff3cd; border: 1px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 5px;'>
                    <p style='margin: 0;'><strong>What's Next?</strong></p>
                    <p style='margin: 10px 0 0 0;'>We will process your order and notify you once it's ready for delivery.</p>
                </div>
                
                <p>If you have any questions about your order, please contact us at:</p>
                <p>📧 info@adiari.shop<br>📞 080-3408-8044</p>
                
                <p>Thank you for shopping with us!</p>
                <p><strong>ADI ARI FRESH VEGETABLES AND HALAL FOOD</strong></p>
            </div>
            
            <div style='text-align: center; padding: 20px; color: #666; font-size: 0.9em;'>
                <p>This is an automated message. Please do not reply to this email.</p>
            </div>
        </body>
        </html>
        ";
    }
    
    /**
     * Get new order notification template for admin
     */
    private static function getNewOrderNotificationTemplate($order, $items) {
        $orderNumber = htmlspecialchars($order['order_number']);
        $customerName = htmlspecialchars($order['shipping_first_name'] . ' ' . $order['shipping_last_name']);
        $customerEmail = htmlspecialchars($order['shipping_email']);
        $customerPhone = htmlspecialchars($order['shipping_phone']);
        $totalAmount = number_format($order['total_amount']);
        
        $itemsHtml = '';
        foreach ($items as $item) {
            $name = htmlspecialchars($item['product_name']);
            $qty = (int)$item['quantity'];
            $price = number_format($item['unit_price']);
            $total = number_format($item['total_price']);
            
            $itemsHtml .= "
                <tr>
                    <td style='padding: 10px; border-bottom: 1px solid #eee;'>{$name}</td>
                    <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: center;'>{$qty}</td>
                    <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: right;'>¥{$price}</td>
                    <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: right;'>¥{$total}</td>
                </tr>
            ";
        }
        
        $address = htmlspecialchars($order['shipping_address_line1']);
        if ($order['shipping_address_line2']) {
            $address .= ', ' . htmlspecialchars($order['shipping_address_line2']);
        }
        $address .= '<br>' . htmlspecialchars($order['shipping_city']);
        if ($order['shipping_state']) {
            $address .= ', ' . htmlspecialchars($order['shipping_state']);
        }
        if ($order['shipping_postal_code']) {
            $address .= ' ' . htmlspecialchars($order['shipping_postal_code']);
        }
        $address .= '<br>' . htmlspecialchars($order['shipping_country']);
        
        $paymentMethod = ucfirst($order['payment_method']);
        
        $notes = $order['customer_notes'] ? htmlspecialchars($order['customer_notes']) : 'None';
        
        $taxLineItem = '';
        if (isset($order['tax_amount']) && $order['tax_amount'] > 0) {
            $taxLineItem = "<p><strong>Tax Amount:</strong> ¥" . number_format($order['tax_amount']) . "</p>";
        }
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>New Order Notification</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: #2196F3; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;'>
                <h1 style='margin: 0;'>🔔 New Order Received!</h1>
            </div>
            
            <div style='background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 0 0 5px 5px;'>
                <p><strong>You have received a new order!</strong></p>
                
                <div style='background: white; padding: 15px; margin: 20px 0; border-radius: 5px;'>
                    <h2 style='margin-top: 0; color: #2196F3;'>Order Information</h2>
                    <p><strong>Order Number:</strong> {$orderNumber}</p>
                    <p><strong>Payment Method:</strong> {$paymentMethod}</p>
                    <p><strong>Total Amount:</strong> <span style='color: #4CAF50; font-size: 1.2em; font-weight: bold;'>¥{$totalAmount}</span></p>
                    {$taxLineItem}
                </div>
                
                <div style='background: white; padding: 15px; margin: 20px 0; border-radius: 5px;'>
                    <h3 style='margin-top: 0;'>Customer Information</h3>
                    <p><strong>Name:</strong> {$customerName}</p>
                    <p><strong>Email:</strong> {$customerEmail}</p>
                    <p><strong>Phone:</strong> {$customerPhone}</p>
                    <p><strong>Address:</strong><br>{$address}</p>
                </div>
                
                <div style='background: white; padding: 15px; margin: 20px 0; border-radius: 5px;'>
                    <h3 style='margin-top: 0;'>Order Items</h3>
                    <table style='width: 100%; border-collapse: collapse;'>
                        <thead>
                            <tr style='background: #f0f0f0;'>
                                <th style='padding: 10px; text-align: left;'>Product</th>
                                <th style='padding: 10px; text-align: center;'>Qty</th>
                                <th style='padding: 10px; text-align: right;'>Price</th>
                                <th style='padding: 10px; text-align: right;'>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$itemsHtml}
                        </tbody>
                    </table>
                </div>
                
                <div style='background: white; padding: 15px; margin: 20px 0; border-radius: 5px;'>
                    <h3 style='margin-top: 0;'>Customer Notes</h3>
                    <p>{$notes}</p>
                </div>
                
                <div style='background: #e3f2fd; border: 1px solid #2196F3; padding: 15px; margin: 20px 0; border-radius: 5px;'>
                    <p style='margin: 0;'><strong>Action Required:</strong></p>
                    <p style='margin: 10px 0 0 0;'>Please log in to the admin panel to process this order.</p>
                </div>
            </div>
            
            <div style='text-align: center; padding: 20px; color: #666; font-size: 0.9em;'>
                <p>This is an automated notification.</p>
            </div>
        </body>
        </html>
        ";
    }
}
