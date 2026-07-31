<?php

/* ---------- CONFIG ---------- */
$to = "faith.chepngetich@zetech.ac.ke";   
$siteName = "Fraud Reporting Form";

/* ---------- CAPTCHA GENERATION ---------- */
if (!isset($_SESSION['captcha'])) {
    $a = random_int(1,9);
    $b = random_int(1,9);
    $_SESSION['captcha'] = $a + $b;
    $_SESSION['captcha_question'] = "$a + $b";
}

/* ---------- FORM PROCESSING ---------- */
$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $captcha = trim($_POST['captcha'] ?? '');

    /* Validation */
    if ($name === '') $errors[] = "Full name required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email required.";
    if ($subject === '') $errors[] = "Subject required.";
    if ($message === '') $errors[] = "Message required.";
    if ($captcha != $_SESSION['captcha']) $errors[] = "Captcha incorrect.";

    /* If valid, send email */
    if (!$errors) {

        $safeMessage =
            "Fraud report submitted:\n\n".
            "Name: ".htmlspecialchars($name)."\n".
            "Email: ".htmlspecialchars($email)."\n".
            "Subject: ".htmlspecialchars($subject)."\n\n".
            "Message:\n".htmlspecialchars($message);

        $headers = [
            "From: $siteName <$to>",
            "Reply-To: $email",
            "Content-Type: text/plain; charset=UTF-8"
        ];

        mail($to, "Fraud Report: $subject", $safeMessage, implode("\r\n",$headers));

        unset($_SESSION['captcha']); // reset captcha
        $success = true;
    }
}
?>

<p>Write to us</p>

<?php if ($success): ?>
<p class="success">Thank you. Your report has been sent.</p>
<?php else: ?>

<?php foreach ($errors as $e): ?>
<p class="error"><?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<div class="card shadow-sm">
  <div class="card-body">

<form method="post" action="" class="mt-3">

<div class="row">

    <div class="form-group col-md-6">
        <label>Full Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="form-group col-md-6">
        <label>Email <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="form-group col-md-6">
        <label>Subject <span class="text-danger">*</span></label>
        <input type="text" name="subject" class="form-control" required>
    </div>

    <div class="form-group col-md-6">
        <label>Solve: <?= $_SESSION['captcha_question']; ?> = ? 
        <span class="text-danger">*</span></label>
        <input type="text" name="captcha" class="form-control" required>
    </div>

    <div class="form-group col-md-12">
        <label>Message <span class="text-danger">*</span></label>
        <textarea name="message" rows="6" class="form-control" required></textarea>
    </div>

</div>

<div class="text-center mt-3">
    <button type="submit" class="btn btn-primary px-4">
        Submit Report
    </button>
</div>

</form>

  </div>
</div>

<?php endif; ?>

