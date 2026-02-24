<?php
// Email Configuration
define('SMTP_FROM', 'noreply@taskmasters.com');
define('SMTP_FROM_NAME', 'TaskMasters');
define('SUPPORT_EMAIL', 'support@taskmasters.com');
define('SUPPORT_PHONE', '+91 7041707025');

function sendOrderConfirmationEmail($orderData) {
    $to = $orderData['email'];
    $subject = "Order Confirmation – TaskMasters";
    
    // Professional HTML Email Template
    $message = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body { margin: 0; padding: 0; font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif; background-color: #f3f4f6; }
            .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center; }
            .logo { width: 60px; height: 60px; background: white; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; font-weight: bold; color: #667eea; margin-bottom: 15px; }
            .header h1 { color: white; margin: 0; font-size: 28px; font-weight: 700; }
            .content { padding: 40px 30px; }
            .order-id-box { background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%); border: 2px solid #667eea; border-radius: 12px; padding: 20px; text-align: center; margin: 30px 0; }
            .order-id { font-size: 32px; font-weight: 800; color: #667eea; letter-spacing: 1px; }
            .details-table { width: 100%; border-collapse: collapse; margin: 25px 0; }
            .details-table td { padding: 12px 0; border-bottom: 1px solid #e5e7eb; }
            .details-table td:first-child { color: #6b7280; font-weight: 500; }
            .details-table td:last-child { text-align: right; font-weight: 600; color: #111827; }
            .status-badge { display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; }
            .status-paid { background: #fef3c7; color: #92400e; }
            .status-verified { background: #d1fae5; color: #065f46; }
            .info-box { background: #eff6ff; border-left: 4px solid #3b82f6; padding: 20px; border-radius: 8px; margin: 25px 0; }
            .info-box h3 { margin: 0 0 10px 0; color: #1e40af; font-size: 16px; }
            .info-box p { margin: 5px 0; color: #1e3a8a; font-size: 14px; line-height: 1.6; }
            .footer { background: #111827; color: #9ca3af; padding: 30px; text-align: center; font-size: 14px; }
            .footer a { color: #667eea; text-decoration: none; }
            .button { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class="container">
            <!-- Header -->
            <div class="header">
                <div class="logo">T</div>
                <h1>Order Confirmed!</h1>
                <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0 0;">Thank you for choosing TaskMasters</p>
            </div>
            
            <!-- Content -->
            <div class="content">
                <p style="font-size: 16px; color: #374151; margin-bottom: 20px;">
                    Hi <strong>' . htmlspecialchars($orderData['name']) . '</strong>,
                </p>
                <p style="font-size: 16px; color: #374151; line-height: 1.6;">
                    Your order has been successfully placed! We have received your payment and our team will start working on your project shortly.
                </p>
                
                <!-- Order ID Box -->
                <div class="order-id-box">
                    <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px; font-weight: 500;">YOUR ORDER ID</p>
                    <div class="order-id">' . htmlspecialchars($orderData['order_id']) . '</div>
                </div>
                
                <!-- Order Details -->
                <h2 style="color: #111827; font-size: 20px; margin: 30px 0 15px 0;">Order Details</h2>
                <table class="details-table">
                    <tr>
                        <td>Service</td>
                        <td>' . htmlspecialchars($orderData['service']) . '</td>
                    </tr>
                    <tr>
                        <td>Amount Paid</td>
                        <td style="color: #667eea; font-size: 18px;">₹' . number_format($orderData['final_price'], 2) . '</td>
                    </tr>
                    <tr>
                        <td>Payment Status</td>
                        <td><span class="status-badge status-' . $orderData['payment_status'] . '">' . ucfirst($orderData['payment_status']) . '</span></td>
                    </tr>
                    <tr>
                        <td>Order Date</td>
                        <td>' . date('d M Y, h:i A', strtotime($orderData['created_at'])) . '</td>
                    </tr>
                </table>
                
                <!-- What\'s Next -->
                <div class="info-box">
                    <h3>📋 What happens next?</h3>
                    <p>1. Our team will verify your payment within 2-4 hours</p>
                    <p>2. We\'ll start working on your project immediately after verification</p>
                    <p>3. You\'ll receive the completed work within 24-48 hours via email</p>
                    <p>4. We\'ll notify you at every step of the process</p>
                </div>
                
                <p style="font-size: 14px; color: #6b7280; margin-top: 30px;">
                    If you have any questions or need to make changes to your order, please reply to this email or contact our support team.
                </p>
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <p style="margin: 0 0 15px 0; font-size: 16px; color: #d1d5db;"><strong>Need Help?</strong></p>
                <p style="margin: 5px 0;">
                    📧 Email: <a href="mailto:' . SUPPORT_EMAIL . '">' . SUPPORT_EMAIL . '</a>
                </p>
                <p style="margin: 5px 0;">
                    📞 Phone: <a href="tel:' . SUPPORT_PHONE . '">' . SUPPORT_PHONE . '</a>
                </p>
                <p style="margin: 20px 0 10px 0; padding-top: 20px; border-top: 1px solid #374151; color: #6b7280; font-size: 12px;">
                    © 2024 TaskMasters. All rights reserved.<br>
                    Professional Digital Services Platform
                </p>
            </div>
        </div>
    </body>
    </html>';
    
    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . SMTP_FROM_NAME . " <" . SMTP_FROM . ">" . "\r\n";
    $headers .= "Reply-To: " . SUPPORT_EMAIL . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Send email
    try {
        $sent = mail($to, $subject, $message, $headers);
        return $sent;
    } catch (Exception $e) {
        error_log("Email sending failed: " . $e->getMessage());
        return false;
    }
}

// Function to send payment verification email
function sendPaymentVerifiedEmail($orderData) {
    $to = $orderData['email'];
    $subject = "Payment Verified – Order in Progress – TaskMasters";
    
    $message = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { margin: 0; padding: 0; font-family: "Inter", Arial, sans-serif; background-color: #f3f4f6; }
            .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
            .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 40px 30px; text-align: center; color: white; }
            .content { padding: 40px 30px; }
            .success-icon { font-size: 64px; margin-bottom: 20px; }
            .footer { background: #111827; color: #9ca3af; padding: 30px; text-align: center; font-size: 14px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <div class="success-icon">✓</div>
                <h1 style="margin: 0; font-size: 28px;">Payment Verified!</h1>
                <p style="margin: 10px 0 0 0; opacity: 0.9;">Your order is now in progress</p>
            </div>
            <div class="content">
                <p>Hi <strong>' . htmlspecialchars($orderData['name']) . '</strong>,</p>
                <p>Great news! Your payment has been verified and our team has started working on your order.</p>
                <p style="background: #f0fdf4; border-left: 4px solid #10b981; padding: 15px; border-radius: 4px; margin: 20px 0;">
                    <strong>Order ID:</strong> ' . htmlspecialchars($orderData['order_id']) . '<br>
                    <strong>Service:</strong> ' . htmlspecialchars($orderData['service']) . '<br>
                    <strong>Expected Delivery:</strong> Within 24-48 hours
                </p>
                <p>We\'ll notify you as soon as your work is completed.</p>
            </div>
            <div class="footer">
                <p>© 2024 TaskMasters. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>';
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: " . SMTP_FROM_NAME . " <" . SMTP_FROM . ">" . "\r\n";
    
    return mail($to, $subject, $message, $headers);
}
?>
