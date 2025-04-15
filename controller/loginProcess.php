<?php
session_start();
require_once '../autoloader.php';
use Employee11\Db\Database;

class LoginProcess
{
    public function handleRequest()
    {
        try {
            $db = Database::getInstance();
            if (!empty($_POST['email']) && !empty($_POST['password'])) {
                if ($db->login($_POST['email'], $_POST['password'])) {
                    $_SESSION['email'] = $_POST['email'];
                    header("Location: ../webpage/dashboard.php");
                    exit();
                } else {
                    $_SESSION['msg'] = "Invalid email or password!";
                    header("Location: ../index.php");
                    exit();
                }
            } else {
                $_SESSION['msg'] = "Please enter both your email and password.";
                header("Location: ../index.php");
                exit();
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// Create object and call function
$login = new LoginProcess();
$login->handleRequest();
?>