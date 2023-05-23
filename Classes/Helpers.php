<?php
namespace Classes;
Class Helpers {
    /** failed constant for queries */
    public const failed = "failed";
    /** success constant */
    public const success = "success";
    /** for login.php  module */
    public const granted = "granted";
    /** for not existing users */
    public const notExisting = "not_existing";
    /** incomplete fields */
    public const incompleteFields = "incomplete_fields";

    public function cleanString($value) {
        /** Removes leading and trailing spaces */
        $data = trim($value);
        /** Removes Unwanted Characters */
        $data = filter_var($data, FILTER_SANITIZE_STRING);
        /** Sanitizes HTML Characters */
        $data = htmlspecialchars_decode($data, ENT_QUOTES);

        return $data;
    }

    public function cleanNumber($value) {
        /** Removes leading and trailing spaces */
        $data = trim($value);
        /** Removes Unwanted Characters */
        $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
        /** Sanitizes HTML Characters */
        $data = htmlspecialchars_decode($data, ENT_QUOTES);

        return $data;
    }

    /** Email Checker */
    public function cleanEmail($data) {
        /** Removes leading and trailing spaces */
        $data = trim($data);
        /** Removes Unwanted Characters */
        $data = filter_var($data, FILTER_VALIDATE_EMAIL);
        /** Sanitizes HTML Characters */
        $data = htmlspecialchars_decode($data, ENT_QUOTES);

        return !preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $data) ? FALSE : TRUE;
    }

    /** check if user is authorized */
    function checkToken($token) {
        if ($_SESSION['token'] !== $token) {
            return false;
        }
    }

    /** verify request types */
    public function checkRequestType($type) {
        if ($_SERVER['REQUEST_METHOD'] !== $type) {
            $this->halt('wrong_request_type');
        } 
    }

    /** stopper method */
    public function halt($flag) {
        if (isset($statement)) {
            $statement->close();
        }
        if (isset($connection)) {
            $connection->close();
        }
        die(json_encode(['status' => $flag]));
    }

    public function ssl_encrypt($password, $method, $keycode) {
        $data = openssl_encrypt($password, $method, $keycode);
        return $data;

    }
    public function ssl_decrypt($password, $method, $keycode) {
        $data = openssl_decrypt($password, $method, $keycode);
        return $data;
    }
    /** response giver */
    public function response_now($statement, $connection,$value) {
        if (isset($statement)) { 
            $statement->close(); 
        }
        if (isset($connection)) {
            // COMMITS THE QUERIES
            $connection->commit();
            $connection->close();
        }
        die(json_encode($value));
    }
}
?>
