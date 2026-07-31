<?php

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
$captchakey = generateRandomString(4);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $captcha = trim($_POST['captchakey']);
    $captcha2 = trim($_POST['captchakey2']);
   
    
     if ($captcha !== $captcha2) {
        header("Location: apply-now?success=2");
          exit();
    }
     

    $data = [
        "name" => trim($_POST['fname']) . " " . trim($_POST['lname']),
        "email" => trim($_POST['email']),
        "phone" => trim($_POST['phoneNumber']),
        "course" => trim($_POST['course']),
        "country"=>trim($_POST['country']),
        "contact"=>trim($_POST['contact']),
        "comments" => trim($_POST['message'])
    ];

    $url = "https://crm.pemarc.com/websiteinquiries";

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            'X-API-KEY: 9fde827743247915d68bfe3f51c7e758796abc4ee56868f5',
            "Accept: application/json"
        ]
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        die("cURL Error: " . curl_error($ch));
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

  // Decode the JSON response
$result = json_decode($response, true);

if (isset($result['status']) && $result['status'] == 1) {

    header("Location: apply-now?success=1");
    exit();

} else {

    header("Location: apply-now?success=0");
    exit();

}
}
?>
  <div class="container-fluid d-flex" id="contact">

    

    <div class="form">
       
     <?php
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo '<div class="alert alert-success">
            Your enquiry was submitted successfully.
          </div>';
}

if (isset($_GET['success']) && $_GET['success'] == 0) {
    echo '<div class="alert alert-danger">
            There was a problem submitting your enquiry. Please try again.
          </div>';
}
if (isset($_GET['success']) && $_GET['success'] == 2) {
    echo '<div class="alert alert-danger">
            Incorrect security code,please try again
          </div>';
}


?>

<form method="POST" action="" id="">

        <div class="col-md-6">

          <label for="fname" class="form-label">

            First Name <span style="color:red">*</span>

          </label>

          <input type="text" class="form-control" id="fname" name="fname" aria-label="First name" required value="<?php echo htmlspecialchars($fname); ?>" />

          <span class="error-message" aria-live="polite"></span>

        </div>

        

        <div class="col-md-6">

          <label for="lname" class="form-label">

            Last Name <span style="color:red">*</span>

          </label>

          <input type="text" class="form-control" id="lname" name="lname" aria-label="Last name" required value="<?php echo htmlspecialchars($lname); ?>" />

          <span class="error-message" aria-live="polite"></span>

        </div>

        

        <div class="col-md-6">

          <label for="email" class="form-label">

            Email <span style="color:red">*</span>

          </label>

          <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($email); ?>" />

          <span class="error-message" aria-live="polite"></span>

        </div>

        

        <div class="col-md-6">

          <label for="phoneNumber" class="form-label">

            Phone Number <span style="color:red">*</span>

          </label>

          <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" required value="<?php echo htmlspecialchars($phone); ?>" />

          <span class="error-message" aria-live="polite"></span>

        </div>
        <div class="col-md-6">

          <label for="country" class="form-label">

            Country <span style="color:red">*</span>

          </label>

          <input type="text" class="form-control" id="country" name="country" required value="<?php echo htmlspecialchars($country); ?>" />

          <span class="error-message" aria-live="polite"></span>

        </div>

  

        <div class="col-md-6">
          <label for="classYear">Courses</label><span style="color:red">*</span>
          <select name="course" class="form-control" id="course" required>
            <option value="">Select Course</option>
            <option value="Lean Sigma">Lean Sigma</option>
            <option value="Prince 2">Prince 2</option>
            <option value="PMI-ACP">PMI-ACP</option>
            <option value="PMP">PMP</option>
            <option value="Data Analytics">Data Analytics</option>
            <option value="Comptia Security">Comptia Security+</option>
          
          </select>
          <span class="error-message" aria-live="polite"></span>
        </div>
        <div class="col-md-6">
          <label for="contactmethod" >Preferred Contact Method</label><span style="color:red">*</span>
          <select name="contact" class="form-control" id="contact" required>
            <option value="">Preferred Contact Method</option>
            <option value="whatsapp">Whats App</option>
            <option value="calls">Call</option>
            <option value="email">Email</option>
            <option value="messages">Messages</option>
          </select>
          <span class="error-message" aria-live="polite"></span>
        </div>
         <div class="col-md-6">
          <label for="source" >How did you learn about us?</label> <span style="color:red">*</span>
          <select name="source" class="form-control" id="source" required>
            <option value="">How did you learn about us?</option>
            <option value="Referral">Referral</option>
            <option value="Whatsapp">WhatsApp</option>
            <option value="Facebook">Facebook</option>
            <option value="LinkedIn">LinkedIn</option>
            <option value="Google">Google</option>
            <option value="Instagram">Instagram</option>
            <option value="Friend">Friend</option>
            <option value="Colleague">Colleague</option>
            <option value="Previous student">Previous student</option>
          </select>
          <span class="error-message" aria-live="polite"></span>
        </div>


        <div class="col-md-12">

          <label for="message" class="form-label">

            Message <span style="color:red">*</span>

          </label>

          <textarea class="form-control" id="message" rows="3" name="message" required><?php echo htmlspecialchars($message); ?></textarea>

        </div>
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
        

        <div class="col-md-12" style="margin:10px !important">

          <button type="submit" class="btn btn-lg qa-apply" id="submit-button">

            Submit

          </button>

        </div>

      </form>

    </div>

  </div>

