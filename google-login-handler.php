<?php
session_start();
include __DIR__ . '/config.php';

// reCAPTCHA configuration
define('RECAPTCHA_SECRET_KEY', 'AIzaSyCPYP0CESBYh0k2ojXtE9rw6hQ6EFnKu2s'); // Replace with your actual secret key

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    if (empty($recaptchaResponse)) {
        $error = "Please complete the reCAPTCHA verification.";
    } else {
        $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptchaData = [
            'secret' => RECAPTCHA_SECRET_KEY,
            'response' => $recaptchaResponse,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];

        $recaptchaOptions = [
            'http' => [
                'method' => 'POST',
                'content' => http_build_query($recaptchaData),
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n"
            ]
        ];

        $recaptchaContext = stream_context_create($recaptchaOptions);
        $recaptchaResult = file_get_contents($recaptchaUrl, false, $recaptchaContext);
        $recaptchaJson = json_decode($recaptchaResult);

        if (!$recaptchaJson->success || ($recaptchaJson->score ?? 1) < 0.5) {
            $error = "reCAPTCHA verification failed. Please try again.";
        } else {
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');

            if ($password !== $confirmPassword) {
                $error = "Passwords do not match.";
            } else {
                $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $error = "Email already registered.";
                } else {
                    // Generate unique username
                    $baseUsername = strtolower($firstName . '.' . $lastName);
                    $username = $baseUsername;
                    $counter = 1;

                    $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
                    do {
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        if ($res->num_rows > 0) {
                            $username = $baseUsername . $counter++;
                        }
                    } while ($res->num_rows > 0);

                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, first_name, last_name, currency, profile_image)
                                            VALUES (?, ?, ?, ?, ?, 'USD', 'default.png')");
                    $stmt->bind_param("sssss", $username, $email, $passwordHash, $firstName, $lastName);

                    if ($stmt->execute()) {
                        $success = "Registration successful! You can now login.";
                    } else {
                        $error = "Registration failed. Please try again.";
                    }
                }
            }
        }
    }
}
?>
