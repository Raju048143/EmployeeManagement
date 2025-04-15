<?php
session_start();
require_once '../autoloader.php';
use Employee11\Db\Database;
 class ProfilePicUpdate{
    public function handleRequest() {
        if (!isset($_SESSION['email'])) {
            $_SESSION['msg'] = "Please Login First!";
            header("Location: ../index.php");
            exit;
        }
        try{
            $db = Database::getInstance();
            if (!empty($_FILES['ProfileImage'])) {
              
                $email = $_SESSION['email'];
                $uploadDir = '../uploads/';
                $fileTmpPath = $_FILES['ProfileImage']['tmp_name'];
                $fileName = basename($_FILES['ProfileImage']['name']);
                $uploadFilePath = $uploadDir . $fileName;
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
            
                if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {

                    if ($db->profilepictureupdate($email, $fileName)) {
                        echo "Profile picture updated successfully!";
                    } else {
                        echo "Database update failed.";
                    }
                } else {
                    echo "File upload failed.";
                }
            } else {
                echo "Profile input Filed is Empty";
            }
        }catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }
 }

 $profilePic = new ProfilePicUpdate();
 $profilePic->handleRequest();
?>
