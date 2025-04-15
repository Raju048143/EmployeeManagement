<?php
session_start();
require_once '../autoloader.php';
use Employee11\Db\Database;
class ProfileUpdate
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
            if (!empty($_POST["id"]) && !empty($_POST["newValue"]) && !empty($_POST["updateType"])) {
                $id = trim($_POST["id"]);
                $newValue = trim($_POST["newValue"]);
                $updateType = trim($_POST["updateType"]);
                $result = false;

                if ($updateType == 'name') {
                    $result = $db->nameUpdate($id, $newValue);
                } elseif ($updateType == 'qualification') {
                    $result = $db->qualificationUpdate($id, $newValue);
                } elseif ($updateType == 'experience') {
                    $result = $db->experienceUpdate($id, $newValue);
                }

                if ($result) {
                    echo "updated successfully!";
                } else {
                    echo "Failed to update $updateType.";
                }
            } else {
                echo "Invalid request parameters.";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

$qualification = new ProfileUpdate();
$qualification->handleRequest();
?>