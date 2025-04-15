<?php
session_start();
require_once '../autoloader.php';
use Employee11\Db\Database;
class RegistrationProcess
{

    public function handleRequest()
    {
        try {
            $db = Database::getInstance();
            $fullName = $_POST['fullName'];
            $dob = $_POST['dob'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];
            $profilePic = $_FILES['profilePic'];
            $permanentAddress = [$_POST['permanentLine1'], $_POST['permanentLine2'], $_POST['permanentCity'], $_POST['permanentState']];
            $currentAddress = [$_POST['currentLine1'], $_POST['currentLine2'], $_POST['currentCity'], $_POST['currentState']];
            $qualification = $_POST['qualifications'];
            $experience = $_POST['experiences'];
            //check email in database
            if ($db->emailCheck($email)) {
                $_SESSION['msg'] = "Email is already registered. Please use another email.";
                header("Location: ../Webpage/registration.php");
                exit();
            }

            $imagePath = $this->handleProfileUpload($profilePic);
            // Call register method
            if ($db->register($fullName, $dob, $email, $password, $confirmPassword, $imagePath, $permanentAddress, $currentAddress, $qualification, $experience)) {
                $_SESSION['email'] = $email;
                header("Location: ../Webpage/dashboard.php");
                exit;
            } else {
                $_SESSION['msg'] = "Registration Failed!";
                header("Location: ../Webpage/registration.php");
                exit;
            }

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function handleProfileUpload($profilePic)
    {
        if (!$profilePic || $profilePic['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowedTypes = ['image/jpeg', 'image/png'];
        if (!in_array($profilePic['type'], $allowedTypes)) {
            throw new Exception("Only JPG and PNG files are allowed for profile pictures.");
        }

        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
            throw new Exception("Failed to create upload directory.");
        }

        $uniqueFileName = uniqid() . '_' . basename($profilePic['name']);
        $uploadFilePath = $uploadDir . $uniqueFileName;

        if (!move_uploaded_file($profilePic['tmp_name'], $uploadFilePath)) {
            throw new Exception("Failed to upload profile picture.");
        }

        return 'uploads/' . $uniqueFileName;
    }

}


$qualification = new RegistrationProcess();
$qualification->handleRequest();
?>