<?php
session_start();
require_once '../autoloader.php';
use Employee11\Db\Database;
class AddQualification
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
            if (!empty($_POST['qualification_name'])) {
                $qualificationName = $_POST['qualification_name'];
                $email = $_SESSION['email'];
                if ($db->addQualification($email, $qualificationName)) {
                    echo "Qualification Added successfully";
                } else {
                    echo 'Failed to add qualification. Please try again.';
                }
            } else {
                echo 'Qualification name cannot be empty.';
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

$qualification = new AddQualification();
$qualification->handleRequest();
?>