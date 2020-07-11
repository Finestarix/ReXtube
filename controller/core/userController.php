<?php

require_once(dirname(__FILE__) . '/databaseController.php');

if (!function_exists('getUserByEmail')) {
    function getUserByEmail($userEmail)
    {
        $connection = getConnection();

        $query = "SELECT * FROM `users` WHERE `email` = ?";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("s", $userEmail);
        $preparedStatement->execute();

        $result = $preparedStatement->get_result();

        return $result->fetch_object();
    }
}

if (!function_exists('getUserByID')) {
    function getUserByID($userID)
    {
        $connection = getConnection();

        $query = "SELECT * FROM `users` WHERE `id` = ?";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("s", $userID);
        $preparedStatement->execute();

        $result = $preparedStatement->get_result();

        return $result->fetch_object();
    }
}

if (!function_exists('validateUserDate')) {
    function validateUserData($oldUser, $newUser)
    {
        return ($oldUser->name == $newUser->name &&
            $oldUser->email == $newUser->email &&
            $oldUser->image == $newUser->image);
    }
}

if (!function_exists('insertUser')) {
    function insertUser($user)
    {
        $connection = getConnection();

        $query = "INSERT INTO `users` (`id`, `name`, `email`, `image`) VALUES (?, ?, ?, ?)";

        $prepareStatement = $connection->prepare($query);
        $prepareStatement->bind_param("ssss",
            $user->id,
            $user->name,
            $user->email,
            $user->image);
        $prepareStatement->execute();
    }
}

if (!function_exists('updateUser')) {
    function updateUser($userID, $user)
    {
        $connection = getConnection();

        $query = "UPDATE `users` SET `name`=?, `email`=?, `image`=? WHERE `id`=?";

        $prepareStatement = $connection->prepare($query);
        $prepareStatement->bind_param("ssss",
            $user->name,
            $user->email,
            $user->image,
            $userID);
        $prepareStatement->execute();
    }
}

