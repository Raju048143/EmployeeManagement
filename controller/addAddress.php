<?php
session_start();
require_once '../autoloader.php';
use Employee11\Db\Database;
class AddAddress
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
            if (!empty($_POST['line1']) && !empty($_POST['line2']) && !empty($_POST['city']) && !empty($_POST['state'])) {
                $line1 = $_POST['line1'];
                $line2 = $_POST['line2'];
                $city = $_POST['city'];
                $state = $_POST['state'];
                $email = $_SESSION['email'];
                if ($db->addCurrentAddress($email, $line1, $line2, $city, $state)) {
                    echo 1;
                } else {
                    echo 'Failed to add Address  Field. Please try again.';
                }
            } else {
                echo 'Address  Field can not be empty.';
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

$address = new AddAddress();
$address->handleRequest();
?>