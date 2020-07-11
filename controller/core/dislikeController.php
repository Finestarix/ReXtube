<?php

require_once(dirname(__FILE__) . '/databaseController.php');

if (!function_exists('getTotalDislikeByVideoID')) {
    function getTotalDislikeByVideoID($videoID)
    {
        $connection = getConnection();

        $query = "SELECT COUNT(*) AS totalDislike FROM `dislike_detail` WHERE `video_id` = ?";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("s", $videoID);
        $preparedStatement->execute();

        $result = $preparedStatement->get_result();

        return $result->fetch_object();
    }
}

if (!function_exists('isUserDislike')) {
    function isUserDislike($userID, $videoID)
    {
        $connection = getConnection();

        $query = "SELECT * FROM `dislike_detail` WHERE `user_id` LIKE ? AND `video_id` LIKE ?";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("ss", $userID,$videoID);
        $preparedStatement->execute();

        $result = $preparedStatement->get_result();

        return $result->fetch_object();
    }
}

if (!function_exists('insertDislike')) {
    function insertDislike($userID, $videoID)
    {
        $connection = getConnection();

        $query = "INSERT INTO `dislike_detail` (`user_id`, `video_id`) VALUES (?, ?)";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("ss", $userID, $videoID);
        $preparedStatement->execute();
    }
}

if (!function_exists('removeDislike')) {
    function removeDislike($userID, $videoID)
    {
        $connection = getConnection();

        $query = "DELETE FROM `dislike_detail` WHERE `user_id` LIKE ? AND `video_id` LIKE ?";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("ss", $userID, $videoID);
        $preparedStatement->execute();
    }
}

