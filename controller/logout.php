<?php
session_start();
class Logout{
    public function destroy() {
        session_unset();
        session_destroy();
        header("Location: ../index.php");
        exit;
    }
}
$logout = new Logout();
$logout ->destroy();
?>
