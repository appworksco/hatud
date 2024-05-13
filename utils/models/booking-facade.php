<?php

class BookingFacade extends DBConnection
{

    function fetchRowBookingByUserId($userId)
    {
        $sql = $this->connect()->prepare("SELECT user_id FROM bookings WHERE user_id = ?");
        $sql->execute([$userId]);
        $count = $sql->rowCount();
        return $count;
    }

    function fetchBookingByUserId($userId)
    {
        $sql = $this->connect()->prepare("SELECT * FROM bookings WHERE user_id = ?");
        $sql->execute([$userId]);
        return $sql;
    }

    function fetchBookingById($bookingId)
    {
        $sql = $this->connect()->prepare("SELECT * FROM bookings WHERE id = ?");
        $sql->execute([$bookingId]);
        return $sql;
    }

    function bookNow($userId, $fullname, $mobileNumber, $locationLatitude, $locationLongitude, $destinationLatitude, $destinationLongitude, $fare, $status)
    {
        $sql = $this->connect()->prepare("INSERT INTO bookings (user_id, fullname, mobile_number, location_latitude, location_longitude, destination_latitude, destination_longitude, fare, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $sql->execute([$userId, $fullname, $mobileNumber, $locationLatitude, $locationLongitude, $destinationLatitude, $destinationLongitude, $fare, $status]);
        return $sql;
    }
}
