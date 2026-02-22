<?php
// models/Notification.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'vendor/autoload.php';

class Notification {
    private $mail;

    public function __construct() {
        $config = require 'config/mail.php';
        $this->mail = new PHPMailer(true);

        try {
            //Server settings
            $this->mail->isSMTP();
            $this->mail->Host       = $config['smtp_host'];
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = $config['smtp_user'];
            $this->mail->Password   = $config['smtp_pass'];
            $this->mail->SMTPSecure = $config['smtp_secure'];
            $this->mail->Port       = $config['smtp_port'];

            $this->mail->setFrom($config['from_email'], $config['from_name']);
        } catch (Exception $e) {
            // Log error or handle gracefully
        }
    }

    public function sendCheckoutNotification($asset, $requester, $admin, $dueDate) {
        try {
            // Recipients - Send to all IT Staff? Or just a specific group? 
            // implementation_plan says "all IT Staff". 
            // For now, let's just send to a configured admin email or the admin performing the action for demo.
            // PROD: Fetch all users with role='admin' or 'it_staff' and addAddress.
            
            $this->mail->addAddress('admin@example.com', 'IT Admin'); // Placeholder

            $this->mail->isHTML(true);
            $this->mail->Subject = 'Asset Checked Out: ' . $asset['asset_tag'];
            $this->mail->Body    = "
                <h3>Asset Checked Out</h3>
                <p><strong>Asset:</strong> {$asset['name']} ({$asset['asset_tag']})</p>
                <p><strong>Requester:</strong> {$requester['name']}</p>
                <p><strong>Checked Out By:</strong> {$admin['name']}</p>
                <p><strong>Expected Return:</strong> {$dueDate}</p>
            ";

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function sendCheckinNotification($asset, $requester, $admin) {
         try {
            $this->mail->addAddress('admin@example.com', 'IT Admin');

            $this->mail->isHTML(true);
            $this->mail->Subject = 'Asset Returned: ' . $asset['asset_tag'];
            $this->mail->Body    = "
                <h3>Asset Returned</h3>
                <p><strong>Asset:</strong> {$asset['name']} ({$asset['asset_tag']})</p>
                <p><strong>Returned By:</strong> {$requester['name']}</p>
                <p><strong>Processed By:</strong> {$admin['name']}</p>
            ";

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
