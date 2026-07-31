<?php

ini_set('display_errors', 1);



$errors = [];

$success = "";



$fname =$email = $phone = $subject =$message = "";



if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Sanitize input

  $fname = trim($_POST['fname']);

 

  $email = trim($_POST['email']);

  $phone = trim($_POST['phoneNumber']);

  $subject = trim($_POST['subject']);

 
  $message = trim($_POST['message']);



  // Validate input

  if (empty($fname)) $errors[] = "Name is required.";

  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "A valid email is required.";

  if (empty($phone)) $errors[] = "Phone number is required.";

  if (empty($subject)) $errors[] = "Subject is required.";

  if (empty($message)) $errors[] = "Message is required.";



  if (empty($errors)) {

    $to = "veronicmuthiga@gmail.com";


    $headers = 'From: ' . $email . "\r\n";

    $body = "Name: $fname \r\n"

      . "Email: $email\r\n"

      . "Phone: $phone\r\n"

      . "Message:\r\n$message";

    if (mail($to, $subject, $body, $headers)) {

      $success = "Email sent successfully!";

      // Clear form fields after success

      $fname = $email = $phone = $subject  = $message = "";

    } else {

      $errors[] = "Error sending email!";

    }

  }

}

if(mail("veronicmuthiga@gmail.com","Test","Testing mail")){
    echo "Mail sent";
}else{
    echo "Mail failed";
}

?>





  <div class="container-fluid d-flex" id="contact">

    

    <div class="form">
        <h4>Get in touch with us</h4>
      <?php if (!empty($errors)): ?>

        <div class="alert alert-danger">

          <?php foreach ($errors as $error): ?>

            <div><?php echo htmlspecialchars($error); ?></div>

          <?php endforeach; ?>

        </div>

      <?php endif; ?>

      <?php if ($success): ?>

        <div class="alert alert-success">

          <?php echo htmlspecialchars($success); ?>

        </div>

      <?php endif; ?>

      <form class="row g-3" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="contactForm">

        <div class="col-md-6">

          <label for="fname" class="form-label">

            First Name

          </label>

          <input type="text" class="form-control" id="fname" name="fname" aria-label="First name" required value="<?php echo htmlspecialchars($fname); ?>" />

          <span class="error-message" aria-live="polite"></span>

        </div>

        

        <div class="col-md-6">

          <label for="lname" class="form-label">

            Last Name

          </label>

          <input type="text" class="form-control" id="lname" name="lname" aria-label="Last name" required value="<?php echo htmlspecialchars($lname); ?>" />

          <span class="error-message" aria-live="polite"></span>

        </div>

        

        <div class="col-md-6">

          <label for="email" class="form-label">

            Email

          </label>

          <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($email); ?>" />

          <span class="error-message" aria-live="polite"></span>

        </div>

        

        <div class="col-md-6">

          <label for="phoneNumber" class="form-label">

            Phone Number

          </label>

          <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" required value="<?php echo htmlspecialchars($phone); ?>" />

          <span class="error-message" aria-live="polite"></span>

        </div>

  

        <div class="col-md-12">

          <label for="classYear" class="form-label">

           Courses

          </label>
          <select name="course" class="form-control" id="course">
            <option>Select Course</option>
            <option value="Lean Sigma">Lean Sigma</option>
            <option value="Data Analytics">Data Analytics</option>
            <option value="Comptia Security">Comptia Security+</option>
          
          </select>


          <span class="error-message" aria-live="polite"></span>

        </div>


        <div class="col-12">

          <label for="message" class="form-label">

            Message

          </label>

          <textarea class="form-control" id="message" rows="3" name="message" required><?php echo htmlspecialchars($message); ?></textarea>

        </div>

        

        <div class="col-12">

          <button type="submit" class="btn btn-lg qa-apply" id="submit-button">

            Submit

          </button>

        </div>

      </form>

    </div>

  </div>
