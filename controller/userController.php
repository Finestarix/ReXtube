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
    var_dump($result);
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

function updateUser($user)
{

}

