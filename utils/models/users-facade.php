<?php

class UsersFacade extends DBConnection
{

    function verifyMobileNumber($mobileNumber)
    {
        $sql = $this->connect()->prepare("SELECT mobile_number FROM users WHERE mobile_number = ?");
        $sql->execute([$mobileNumber]);
        $count = $sql->rowCount();
        return $count;
    }

    function signUp($firstName, $middleName, $lastName, $mobileNumber)
    {
        $sql = $this->connect()->prepare("INSERT INTO users (first_name, middle_name, last_name, mobile_number) VALUES (?, ?, ?, ?)");
        $sql->execute([$firstName, $middleName, $lastName, $mobileNumber]);
        return $sql;
    }

    function signIn($mobileNumber)
    {
        $sql = $this->connect()->prepare("SELECT * FROM users WHERE mobile_number = ?");
        $sql->execute([$mobileNumber]);
        return $sql;
    }
   
}
