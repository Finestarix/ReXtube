<?php
require_once(dirname(__FILE__) . '/../model/User.php');
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

if (!function_exists('validateUserDate')) {
    function validateUserData($oldUser, $newUser)
    {
        return ($oldUser->name == $newUser->userName &&
            $oldUser->email == $newUser->userEmail &&
            $oldUser->image == $newUser->userImage);
    }
}

if (!function_exists('insertUser')) {
    function insertUser($user)
    {
        $connection = getConnection();

        $query = "INSERT INTO `users` (`id`, `name`, `email`, `image`) VALUES (?, ?, ?, ?)";

        $prepareStatement = $connection->prepare($query);
        $prepareStatement->bind_param("ssss",
            $user->userID,
            $user->userName,
            $user->userEmail,
            $user->userImage);
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
            $user->userName,
            $user->userEmail,
            $user->userImage,
            $userID);
        $prepareStatement->execute();
    }
}

