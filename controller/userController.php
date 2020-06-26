<?php
include('model/User.php');
include('databaseController.php');

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

function validateUserData($oldUser, $newUser)
{
    return ($oldUser->name == $newUser->userName &&
        $oldUser->email == $newUser->userEmail &&
        $oldUser->image == $newUser->userImage);
}

function insertUser($user)
{
    $connection = getConnection();

    $query = "INSERT INTO `users` (`id`, `name`, `email`, `image`) VALUES (?, ?, ?, ?)";

    $prepareStatement = $connection->prepare($query);
    $prepareStatement->bind_param("ssss",
        $user->userID,
        $user->userName,
        $user->userEmail,
        $user->userProfile);
    $prepareStatement->execute();
}

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

