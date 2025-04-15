<?php
session_start();
require_once '../autoloader.php';
use Employee11\Db\Database;
if (!isset($_SESSION['email'])) {
    $_SESSION['msg'] = "Please Login First!";
    header("Location: ../index.php");
    exit;
}
$email = $_SESSION['email'];

$db = Database::getInstance(); 

$profile = $db->getProfileDetails($email);
$currentAddress = $db->getCurrentAddress($email);
$permanentAddress = $db->getPermanentAddress($email);
$qualifications = $db->getQualifications($email);
$experiences = $db->getExperiences($email);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!-- External CSS -->
    <link rel="stylesheet" href="../resources/css/dashboardPage.css">
    <!-- jQuery -->
    <script src="../resources/js/jquery-3.6.0.min.js"></script>
    <script src="../resources/js/dashboard.js"></script>
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>

<body>
    <div class="container">
        <!-- Profile Section -->
        <section class="profile-section">
            <div>
                <h3>Employee Profile</h3>
            </div>
            <div class="profile-details">
                <div class="edit-icon">
                    <?php if ($profile['ProfileImage'] && file_exists("../uploads/" . basename($profile['ProfileImage']))):?>
                        <img id="profileImage"
                            src="../uploads/<?php echo htmlspecialchars(string: basename($profile['ProfileImage'])); ?>"
                            alt="Profile Image">
                    <?php else: ?>
                        <div class="circle">
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" id="profileImage"height="80px" width="80px"  alt="">
                    </div>
                    <?php endif; ?>
                    <i class="fa fa-edit" id="editIcon"></i>
                </div>
                <div class="profile-details">
                    <p class="editable-field edit-name" id="profileName" data-user-id="<?php echo $email; ?>"
                        data-field="name" >
                        <b><?php echo htmlspecialchars($profile['name']); ?></b>
                    </p>

                    <p class="email-dob"><?php echo htmlspecialchars($profile['email']); ?></p>
                    <p class="email-dob">DOB - <?php echo htmlspecialchars($profile['dob']); ?></p>
                </div>

                <!-- Hidden Profile Picture Input -->
                <form id="uploadForm" style="display: none;" method="POST" enctype="multipart/form-data">
                    <input type="file" name="ProfileImage" id="profileImageInput" accept="image/*">
                </form>
            </div>
        </section>

        <!-- Qualifications -->
        <section class="qualifications-experience">
            <div class="section-box">
                <h3>Qualifications</h3>
                <div id="qualificationList">
                    <?php while ($qualification = $qualifications->fetch_assoc()): ?>
                        <p class="editable-field edit-qualification qualification-item"
                            data-user-id="<?php echo $qualification['qualification_id']; ?>" data-field="qualification">
                            <?php echo htmlspecialchars($qualification['qualification_name']); ?>
                        </p>
                    <?php endwhile; ?>
                    <a href="#" id="addQualificationBtn">Add Qualification</a>
                </div>
                <div id="qualificationInputContainer"></div>
            </div>

            <!-- Experience Section (Editable) -->
            <div class="section-box">
                <h3>Experience</h3>
                <div id="experienceList">
                    <?php while ($experience = $experiences->fetch_assoc()): ?>
                        <p class="editable-field edit-experience experience-item"
                            data-user-id="<?php echo $experience['experience_id']; ?>" data-field="experience">
                            <?php echo htmlspecialchars($experience['jobtitle']); ?>  </p>
                    <?php endwhile; ?>
                    <a href="#" id="addExperienceBtn">Add Experience</a>
                </div>
                <div id="experienceInputContainer"></div>
            </div>
        </section>

        <!-- Address Section -->
        <section class="address-section">
            <div class="address-box">
                <h3>Current Address</h3>
                <?php if ($currentAddress && $currentAddress->num_rows > 0):
                    $address = $currentAddress->fetch_assoc();
                ?>
                <div>
                    <p class="editable-field edit-line1" data-user-id="<?php echo htmlspecialchars($address['address_id']); ?>"
                        data-field="line1" data-type="current">
                        <?php echo htmlspecialchars($address['line1']); ?>
                    </p>

                    <p class="editable-field edit-line2" data-user-id="<?php echo htmlspecialchars($address['address_id']); ?>"
                        data-field="line2" data-type="current">
                        <?php echo htmlspecialchars($address['line2']); ?>
                    </p>

                    <p class="editable-field edit-city" data-user-id="<?php echo htmlspecialchars($address['address_id']); ?>"
                        data-field="city" data-type="current">
                        <?php echo htmlspecialchars($address['city']); ?>
                    </p>

                    <p class="editable-field edit-state" data-user-id="<?php echo htmlspecialchars($address['address_id']); ?>"
                        data-field="state" data-type="current">
                        <?php echo htmlspecialchars($address['state']); ?>
                    </p>
                </div>
                <?php else: ?>
                    <p>No current address available. <button class="add-address-btn" >Add</button></p>
                <?php endif; ?>
            </div>

            <div class="address-box">
                <h3>Permanent Address</h3>
                <?php if ($permanentAddress && $permanentAddress->num_rows > 0):
                    $address = $permanentAddress->fetch_assoc();
                ?>
                <div>
                    <p class="editable-field edit-line1" data-user-id="<?php echo htmlspecialchars($address['address_id']); ?>"
                        data-field="line1" data-type="permanent">
                        <?php echo htmlspecialchars($address['line1']); ?>
                    </p>

                    <p class="editable-field edit-line2" data-user-id="<?php echo htmlspecialchars($address['address_id']); ?>"
                        data-field="line2" data-type="permanent">
                        <?php echo htmlspecialchars($address['line2']); ?>
                    </p>

                    <p class="editable-field edit-city" data-user-id="<?php echo htmlspecialchars($address['address_id']); ?>"
                        data-field="city" data-type="permanent">
                        <?php echo htmlspecialchars($address['city']); ?>
                    </p>

                    <p class="editable-field edit-state" data-user-id="<?php echo htmlspecialchars($address['address_id']); ?>"
                        data-field="state" data-type="permanent">
                        <?php echo htmlspecialchars($address['state']); ?>
                    </p>
                </div>
                <?php else: ?>
                    <p>No permanent address available.</p>
                <?php endif; ?>
            </div>
            <div id="updateSet"
                style="display:none; color: green; position: absolute; top: 10px; left: 50%; transform: translateX(-50%);">
            </div>
            <div id="updateUnSet"
                style="display:none; color: green; position: absolute; top: 10px; left: 50%; transform: translateX(-50%);">
            </div>


        </section>

        <a href="../Controller/logout.php">Logout</a>
    </div>
    
    <div id="address-model">
    <div id="model-form">
        <h4>Add Current Address!</h4>
        <input type="text" name="line1" id="line1" placeholder="Line1"><br>
        <input type="text" name="line2" id="line2" placeholder="Line2"><br>
        <input type="text" name="city" id="city" placeholder="City"><br>
        <input type="text" name="state" id="state" placeholder="State"><br>
        <button class="save">Save</button>
        <div id="close-btn">X</div>
    </div>
</div>

</div>

</div>
</body>

</html>
