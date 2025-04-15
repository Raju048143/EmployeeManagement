<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration Form</title>
    <link rel="stylesheet" href="../resources/css/registrationPage.css">
    <script src="../resources/js/jquery-3.6.0.min.js"></script>
    <script src="../resources/js/registration.js"></script>
</head>

<body>
    <section>
        <div>
            <h3>Employee Registration Form</h3>
        </div>
        <div class="form-container">
        <?php
      if (isset($_SESSION['msg'])) {
          echo '<p style="color:red; text-align:center;">' . $_SESSION['msg'] . '</p>';
          unset($_SESSION['msg']); 
          
      }
    ?>
            <form id="register-form" action="../Controller/registrationProcess.php" method="post" enctype="multipart/form-data">
                <!-- Name, Date of Birth and Profile Picture Section -->
                <div class="name-dob-profile-container">
                    <div class="left-section">
                        <div class="form-group">
                            <label for="fullName">Full Name</label>
                            <input type="text" id="fullName" name="fullName" placeholder="John Doe" required>
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" id="dob" name="dob" required>
                        </div>
                    </div>
                    <div class="right-section">
                        <!-- Profile Picture -->
                        <div class="profile-pic">
                            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile Picture" id="profilePreview">
                            <label for="profilePic" class="add-button">Upload Profile Pic</label>
                            <input type="file" id="profilePic" name="profilePic" accept="image/*" style="display: none;">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="example@email.com" required>
                </div>
                <!-- Password & Re-Password -->
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" 
                        title="Password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a number, and a special character.">
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Re-Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" 
                        title="Password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a number, and a special character.">
                    </div>
                </div>
                <!-- Qualifications -->
                <div class="form-group">
                    <label>Add your Qualifications</label>
                    <label for="addQualification" class="suggession">Qualification 1</label>
                    <input type="text" name="qualifications[]">
                    <label id="addQualification" class="addMore">Add Qualification</label>
                </div>
                <!-- Experiences -->
                <div class="form-group">
                    <label>Add your Experiences</label>
                    <label for="addExperience" class="suggession">Experience 1</label>
                    <input type="text" name="experiences[]">
                    <label id="addExperience" class="addMore">Add Experience</label>
                </div>
                <!-- Permanent Address -->
                <div class="form-group">
                    <label>Permanent Address</label>
                    <input type="text" id="permanentLine1" name="permanentLine1" placeholder="Line 1">
                    <input type="text" id="permanentLine2" name="permanentLine2" placeholder="Line 2">
                </div>
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="permanentCity">City</label>
                        <input type="text" id="permanentCity" name="permanentCity" placeholder="City">
                    </div>
                    <div class="form-group">
                        <label for="permanentState">State</label>
                        <select id="permanentState" name="permanentState">
                            <option value="">Select State</option>
                            <option>Andhra Pradesh</option>
                            <option>Arunachal Pradesh</option>
                            <option>Assam</option>
                            <option>Bihar</option>
                            <option>Chhattisgarh</option>
                            <option>Goa</option>
                            <option>Gujarat</option>
                            <option>Haryana</option>
                            <option>Himachal Pradesh</option>
                            <option>Jharkhand</option>
                            <option>Karnataka</option>
                            <option>Kerala</option>
                            <option>Madhya Pradesh</option>
                            <option>Maharashtra</option>
                            <option>Manipur</option>
                            <option>Meghalaya</option>
                            <option>Mizoram</option>
                            <option>Nagaland</option>
                            <option>Odisha</option>
                            <option>Punjab</option>
                            <option>Rajasthan</option>
                            <option>Sikkim</option>
                            <option>Tamil Nadu</option>
                            <option>Telangana</option>
                            <option>Tripura</option>
                            <option>Uttar Pradesh (UP)</option>
                            <option>Uttarakhand</option>
                            <option>West Bengal</option>
                            <!-- Union Territories -->
                            <option>Andaman and Nicobar Islands</option>
                            <option>Chandigarh</option>
                            <option>Dadra and Nagar Haveli and Daman and Diu</option>
                            <option>Delhi</option>
                            <option>Lakshadweep</option>
                            <option>Delhi</option>
                            <option>Puducherry</option>
                            <option>Ladakh</option>
                            <option> Jammu & Kashmir</option>
                        </select>
                    </div>
                </div>
                <!-- Current Address -->
                <div class="form-group">
                    <label>Current Address</label>
                    <input type="text" id="currentLine1" name="currentLine1" placeholder="Line 1">
                    <input type="text" id="currentLine2" name="currentLine2" placeholder="Line 2">
                </div>
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="currentCity">City</label>
                        <input type="text" id="currentCity" name="currentCity" placeholder="City">
                    </div>
                    <div class="form-group">
                        <label for="currentState">State</label>
                        <select id="currentState" name="currentState">
                            <option value="">Select State</option>
                            <option>Andhra Pradesh</option>
                            <option>Arunachal Pradesh</option>
                            <option>Assam</option>
                            <option>Bihar</option>
                            <option>Chhattisgarh</option>
                            <option>Goa</option>
                            <option>Gujarat</option>
                            <option>Haryana</option>
                            <option>Himachal Pradesh</option>
                            <option>Jharkhand</option>
                            <option>Karnataka</option>
                            <option>Kerala</option>
                            <option>Madhya Pradesh</option>
                            <option>Maharashtra</option>
                            <option>Manipur</option>
                            <option>Meghalaya</option>
                            <option>Mizoram</option>
                            <option>Nagaland</option>
                            <option>Odisha</option>
                            <option>Punjab</option>
                            <option>Rajasthan</option>
                            <option>Sikkim</option>
                            <option>Tamil Nadu</option>
                            <option>Telangana</option>
                            <option>Tripura</option>
                            <option>Uttar Pradesh (UP)</option>
                            <option>Uttarakhand</option>
                            <option>West Bengal</option>
                            <!-- Union Territories -->
                            <option>Andaman and Nicobar Islands</option>
                            <option>Chandigarh</option>
                            <option>Dadra and Nagar Haveli and Daman and Diu</option>
                            <option>Delhi</option>
                            <option>Lakshadweep</option>
                            <option>Delhi</option>
                            <option>Puducherry</option>
                            <option>Ladakh</option>
                            <option> Jammu & Kashmir</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="submit-btn">Sign Up</button>
            </form>
        </div>
    </section>
</body>
</html>
  