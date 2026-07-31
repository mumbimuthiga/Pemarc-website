<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
|--------------------------------------------------------------------------
| CONFIGURATION
|--------------------------------------------------------------------------
*/

$toEmail = "veronicmuthiga@gmail.com";
$bccEmail = "veronicah.mumbi@zetech.ac.ke";
$siteEmail = "info@pemarc.com"; // CHANGE THIS

/*
|--------------------------------------------------------------------------
| VARIABLES
|--------------------------------------------------------------------------
*/

$success = "";
$errors = [];

$fullname = "";
$email = "";
$phone = "";
$subject = "";
$comments = "";

/*
|--------------------------------------------------------------------------
| CAPTCHA FUNCTIONS
|--------------------------------------------------------------------------
*/

function generateRandomString($length = 4)
{
    $characters = '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomString;
}

function drawCaptcha($captcha)
{
    return '
        <span style="font-size:22px;font-weight:bold;letter-spacing:5px;">
            ' . $captcha . '
        </span>
    ';
}

/*
|--------------------------------------------------------------------------
| FORM PROCESSING
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize Inputs
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $subject = trim($_POST['subject']);
    $comments = trim($_POST['comments']);

    $captcha = trim($_POST['captchakey']);
    $captcha2 = trim($_POST['captchakey2']);

    // Validation
    if (empty($fullname)) {
        $errors[] = "Full name is required.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }

    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    }

    if (empty($subject)) {
        $errors[] = "Subject is required.";
    }

    if (empty($comments)) {
        $errors[] = "Message is required.";
    }

    if ($captcha !== $captcha2) {
        $errors[] = "Invalid security code.";
    }

    /*
    |--------------------------------------------------------------------------
    | SEND EMAIL
    |--------------------------------------------------------------------------
    */

    if (empty($errors)) {

        $message = '
        <html>
        <head>
            <title>Website Enquiry</title>
        </head>
        <body>
            <h2>New Website Enquiry</h2>

            <table border="1" cellpadding="10" cellspacing="0" width="100%">
                <tr>
                    <td><strong>Full Name</strong></td>
                    <td>' . htmlspecialchars($fullname) . '</td>
                </tr>

                <tr>
                    <td><strong>Email</strong></td>
                    <td>' . htmlspecialchars($email) . '</td>
                </tr>

                <tr>
                    <td><strong>Phone</strong></td>
                    <td>' . htmlspecialchars($phone) . '</td>
                </tr>

                <tr>
                    <td><strong>Subject</strong></td>
                    <td>' . htmlspecialchars($subject) . '</td>
                </tr>

                <tr>
                    <td><strong>Message</strong></td>
                    <td>' . nl2br(htmlspecialchars($comments)) . '</td>
                </tr>
            </table>
        </body>
        </html>
        ';

        // Headers
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: Website Contact Form <{$siteEmail}>\r\n";
        $headers .= "Reply-To: {$email}\r\n";
        $headers .= "BCC: {$bccEmail}\r\n";

        // Send Mail
        if (mail($toEmail, $subject, $message, $headers)) {

            $success = "Thank you for contacting us. We shall get back to you.";

            // Clear form
            $fullname = "";
            $email = "";
            $phone = "";
            $subject = "";
            $comments = "";

        } else {

            $errors[] = "Failed to send email. Please try again later.";
        }
    }
}

/*
|--------------------------------------------------------------------------
| GENERATE CAPTCHA
|--------------------------------------------------------------------------
*/

$captchakey = generateRandomString(4);
?>

<!-- CONTACT FORM -->

<div class="col-md-12 form-contacts">

    <h3>Write to us</h3>

    <!-- SUCCESS MESSAGE -->
    <?php if (!empty($success)) : ?>

        <div class="alert alert-success">
            <?php echo $success; ?>
        </div>

    <?php endif; ?>

    <!-- ERROR MESSAGES -->
    <?php if (!empty($errors)) : ?>

        <div class="alert alert-danger">
            <ul style="margin:0; padding-left:20px;">

                <?php foreach ($errors as $error) : ?>

                    <li><?php echo $error; ?></li>

                <?php endforeach; ?>

            </ul>
        </div>

    <?php endif; ?>

    <!-- FORM -->
    <form method="post" action="">

        <div class="row">

            <div class="col-md-6">
                <label>Full Name *</label>

                <input
                    type="text"
                    name="fullname"
                    class="form-control"
                    value="<?php echo htmlspecialchars($fullname); ?>"
                    required
                >
            </div>

            <div class="col-md-6">
                <label>Email Address *</label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="<?php echo htmlspecialchars($email); ?>"
                    required
                >
            </div>

        </div>

        <br>

        <div class="row">

            <div class="col-md-6">
                <label>Phone Number *</label>

                <input
                    type="text"
                    name="phone"
                    class="form-control"
                    value="<?php echo htmlspecialchars($phone); ?>"
                    required
                >
            </div>

            <div class="col-md-6">
                <label>Subject *</label>

                <input
                    type="text"
                    name="subject"
                    class="form-control"
                    value="<?php echo htmlspecialchars($subject); ?>"
                    required
                >
            </div>

        </div>

        <br>

        <div class="row">

            <div class="col-md-12">
                <label>Message *</label>

                <textarea
                    name="comments"
                    class="form-control"
                    rows="5"
                    required
                ><?php echo htmlspecialchars($comments); ?></textarea>
            </div>

        </div>

        <br>

        <!-- CAPTCHA -->
        <div class="row">

            <div class="col-md-6">

                <label>Security Code *</label>

                <div style="margin-bottom:10px;">
                    <?php echo drawCaptcha($captchakey); ?>
                </div>

                <input
                    type="text"
                    name="captchakey"
                    class="form-control"
                    placeholder="Enter security code"
                    maxlength="4"
                    required
                >

                <input
                    type="hidden"
                    name="captchakey2"
                    value="<?php echo $captchakey; ?>"
                >

            </div>

        </div>

        <br>

        <div class="row">

            <div class="col-md-6">

                <button type="submit" class="btn btn-primary">
                    Submit
                </button>

            </div>

        </div>

    </form>

</div>
