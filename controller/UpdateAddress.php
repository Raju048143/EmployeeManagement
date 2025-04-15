<?php
session_start();
require_once '../autoloader.php';
use Employee11\Db\Database;
class UpdateAddress
{
    function handleRequest()
    {
        if (!isset($_SESSION['email'])) {
            $_SESSION['msg'] = "Please Login First!";
            header("Location: ../index.php");
            exit;
        }
        try {
            $db = Database::getInstance();
            if (!empty($_POST["address_id"]) && !empty($_POST["field"]) && isset($_POST["newValue"]) && !empty($_POST["addressType"])) {
                $addressId = trim($_POST["address_id"]);
                $field = trim($_POST["field"]);  // Field: line1, line2, city, state
                $newValue = trim($_POST["newValue"]);
                $addressType = trim($_POST["addressType"]);
                $result = false;

                if ($addressType === 'current') {
                    if ($field == 'line1') {
                        $result = $db->updateCurrentAddressLine1($addressId, $newValue);
                    } elseif ($field == 'line2') {
                        $result = $db->updateCurrentAddressLine2($addressId, $newValue);
                    } elseif ($field == 'city') {
                        $result = $db->updateCurrentAddressCity($addressId, $newValue);
                    } elseif ($field == 'state') {
                        $result = $db->updateCurrentAddressState($addressId, $newValue);
                    }
                } elseif ($addressType === 'permanent') {
                    if ($field == 'line1') {
                        $result = $db->updatePermanentAddressLine1($addressId, $newValue);
                    } elseif ($field == 'line2') {
                        $result = $db->updatePermanentAddressLine2($addressId, $newValue);
                    } elseif ($field == 'city') {
                        $result = $db->updatePermanentAddressCity($addressId, $newValue);
                    } elseif ($field == 'state') {
                        $result = $db->updatePermanentAddressState($addressId, $newValue);
                    }
                }

                if ($result) {
                    echo "updated successfully!";
                } else {
                    echo "Failed to update $field.";
                }
            } else {
                echo "Invalid request parameters.";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

$qualification = new UpdateAddress();
$qualification->handleRequest();
?>