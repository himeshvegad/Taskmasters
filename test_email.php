<?php
require_once 'config/email.php';

// Test email data
$testOrder = [
    'order_id' => 'TM20240115ABC123',
    'name' => 'John Doe',
    'email' => 'customer@example.com',
    'phone' => '+91 9876543210',
    'service' => 'Presentation (PPT)',
    'price' => 499,
    'final_price' => 399.20,
    'payment_status' => 'paid',
    'created_at' => date('Y-m-d H:i:s')
];

if (isset($_GET['send'])) {
    $result = sendOrderConfirmationEmail($testOrder);
    echo "<div style='padding: 20px; background: " . ($result ? '#d1fae5' : '#fee2e2') . "; margin: 20px; border-radius: 8px;'>";
    echo $result ? "✓ Email sent successfully!" : "✗ Email sending failed. Check your SMTP settings.";
    echo "</div>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Email Template Preview - TaskMasters</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; padding: 20px; }
        .controls { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .btn { display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 0 10px; }
        .preview { background: white; padding: 20px; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="controls">
        <h1>Email Template Preview & Test</h1>
        <a href="?send=1" class="btn">📧 Send Test Email</a>
        <a href="?" class="btn" style="background: #6b7280;">🔄 Refresh Preview</a>
    </div>
    
    <div class="preview">
        <?php
        // Generate email HTML for preview
        ob_start();
        $orderData = $testOrder;
        include 'config/email.php';
        
        // Display the email template
        echo '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { margin: 0; padding: 0; font-family: "Inter", Arial, sans-serif; background-color: #f3f4f6; }
                .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center; }
                .logo { width: 60px; height: 60px; background: white; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; font-weight: bold; color: #667eea; margin-bottom: 15px; }
                .header h1 { color: white; margin: 0; font-size: 28px; }
                .content { padding: 40px 30px; }
                .order-id-box { background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%); border: 2px solid #667eea; border-radius: 12px; padding: 20px; text-align: center; margin: 30px 0; }
                .order-id { font-size: 32px; font-weight: 800; color: #667eea; }
                .details-table { width: 100%; border-collapse: collapse; margin: 25px 0; }
                .details-table td { padding: 12px 0; border-bottom: 1px solid #e5e7eb; }
                .status-badge { display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 600; background: #fef3c7; color: #92400e; }
                .info-box { background: #eff6ff; border-left: 4px solid #3b82f6; padding: 20px; border-radius: 8px; margin: 25px 0; }
                .footer { background: #111827; color: #9ca3af; padding: 30px; text-align: center; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <div class="logo">T</div>
                    <h1>Order Confirmed!</h1>
                    <p style="color: rgba(255,255,255,0.9);">Thank you for choosing TaskMasters</p>
                </div>
                <div class="content">
                    <p>Hi <strong>' . htmlspecialchars($orderData['name']) . '</strong>,</p>
                    <p>Your order has been successfully placed!</p>
                    <div class="order-id-box">
                        <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">YOUR ORDER ID</p>
                        <div class="order-id">' . htmlspecialchars($orderData['order_id']) . '</div>
                    </div>
                    <h2>Order Details</h2>
                    <table class="details-table">
                        <tr><td>Service</td><td>' . htmlspecialchars($orderData['service']) . '</td></tr>
                        <tr><td>Amount Paid</td><td style="color: #667eea;">₹' . number_format($orderData['final_price'], 2) . '</td></tr>
                        <tr><td>Payment Status</td><td><span class="status-badge">' . ucfirst($orderData['payment_status']) . '</span></td></tr>
                        <tr><td>Order Date</td><td>' . date('d M Y, h:i A') . '</td></tr>
                    </table>
                    <div class="info-box">
                        <h3 style="margin: 0 0 10px 0; color: #1e40af;">📋 What happens next?</h3>
                        <p style="margin: 5px 0;">1. Payment verification within 2-4 hours</p>
                        <p style="margin: 5px 0;">2. Work starts immediately after verification</p>
                        <p style="margin: 5px 0;">3. Delivery within 24-48 hours via email</p>
                    </div>
                </div>
                <div class="footer">
                    <p><strong>Need Help?</strong></p>
                    <p>📧 support@taskmasters.com | 📞 +91 7041707025</p>
                    <p style="margin-top: 20px; font-size: 12px;">© 2024 TaskMasters. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';
        ?>
    </div>
</body>
</html>
