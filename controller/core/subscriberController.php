<?php

require_once(dirname(__FILE__) . '/databaseController.php');

if (!function_exists('getTotalUserSubscriber')) {
    function getTotalUserSubscriber($userID)
    {
        $connection = getConnection();

        $query = "SELECT COUNT(*) AS totalSubscriber FROM `subscribers` WHERE `user_id` LIKE ?";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("s", $userID);
        $preparedStatement->execute();

        $result = $preparedStatement->get_result();

        return $result->fetch_object();
    }
}

if (!function_exists('isSubscribe')) {
    function isSubscribe($userID, $friendID)
    {
        $connection = getConnection();

        $query = "SELECT COUNT(*) AS totalSubscriber FROM `subscribers` WHERE `user_id` LIKE ? AND `friend_id` LIKE ?";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("ss", $userID, $friendID);
        $preparedStatement->execute();

        $result = $preparedStatement->get_result();

        return $result->fetch_object();
    }
}

if (!function_exists('insertSubscriber')) {
    function insertSubscriber($userID, $friendID)
    {
        $connection = getConnection();

        $query = "INSERT INTO `subscribers` (`user_id`, `friend_id`) VALUES (?, ?)";

        $prepareStatement = $connection->prepare($query);
        $prepareStatement->bind_param("ss", $userID, $friendID);
        $prepareStatement->execute();

        var_dump($prepareStatement);

        return $prepareStatement;
    }
}

if (!function_exists('removeSubscriber')) {
    function removeSubscriber($userID, $friendID)
    {
        $connection = getConnection();

        $query = "DELETE FROM `subscribers` WHERE `user_id` LIKE ? AND `friend_id` LIKE ?";

        $prepareStatement = $connection->prepare($query);
        $prepareStatement->bind_param("ss", $userID, $friendID);
        $prepareStatement->execute();

        var_dump($prepareStatement);

        return $prepareStatement;
    }
}