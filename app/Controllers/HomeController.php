<?php
require_once __DIR__ . '/../models/User.php';

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class HomeController {
    public function index() {
        $userModel = new User();
        $email =$userModel->getFirstEmail();

        $emailStatus = $this->sendWelcomeEmail($email);

        $data = [
            'greeting' => 'Hello World',
            'email' => $email,
            'emailStatus' => $emailStatus
        ];

        require_once __DIR__ . '/../views/home.php';
    }

    private function sendWelcomeEmail($toEmail) {
        // Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            // Server settings
            // $mail->SMTPDebug = 2; // Enable verbose debug output if you run into connection issues$mail->isSMTP();                                            // Send using SMTP
            $mail->Host       =$_ENV['SMTP_HOST'];                     // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication$mail->Username   = $_ENV['SMTP_USER'];                     // SMTP username$mail->Password   = $_ENV['SMTP_PASS'];                     // SMTP password$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           // Enable implicit TLS encryption
            $mail->Port       =$_ENV['SMTP_PORT'];                     // TCP port to connect to; use 587 if you have set `ENCRYPTION_STARTTLS`

            // Recipients
            $mail->setFrom('webmaster@example.com', 'Our MVC App');
            $mail->addAddress($toEmail);                                // Add a recipient

            // Content
            $mail->isHTML(true);                                        // Set email format to HTML$mail->Subject = 'Hello from our MVC App!';
            $mail->Body    = '<h1>Hello!</h1><p>This is a test email sent using <strong>PHPMailer</strong> in our plain PHP MVC template.</p>';$mail->AltBody = 'Hello! This is a test email sent using PHPMailer in our plain PHP MVC template.';

            $mail->send();
            return 'Email sent successfully via PHPMailer!';
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}