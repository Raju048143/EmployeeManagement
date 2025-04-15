<?php
session_start();
require_once '../autoloader.php';
use Employee11\Db\Database;
class AddExperience
{
    public function handleRequest()
    {
        if (!isset($_SESSION['email'])) {
            $_SESSION['msg'] = "Please Login First!";
            header("Location: ../index.php");
            exit;
        }
        try {
            $db = Database::getInstance();
            if (!empty($_POST['jobTitle'])) {
                $jobTitle = $_POST['jobTitle'];
                $email = $_SESSION['email'];
                if ($db->addExperience($email, $jobTitle)) {
                    echo "Experience Added successfully";
                } else {
                    echo 'Failed to add experience. Please try again.';
                }
            } else {
                echo 'Experience can not be empty.';
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
$experience = new AddExperience();
$experience->handleRequest();
?>