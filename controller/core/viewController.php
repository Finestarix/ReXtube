<?php

require_once(dirname(__FILE__) . '/databaseController.php');

if (!function_exists('getTotalViewByVideoID')) {
    function getTotalViewByVideoID($videoID)
    {
        $connection = getConnection();

        $query = "SELECT COUNT(*) AS totalView FROM `view_detail` WHERE `video_id` = ?";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("s", $videoID);
        $preparedStatement->execute();

        $result = $preparedStatement->get_result();

        return $result->fetch_object();
    }
}
if (!function_exists('isUserView')) {
    function isUserView($userID, $videoID)
    {
        $connection = getConnection();

        $query = "SELECT * FROM `view_detail` WHERE `user_id` LIKE ? AND `video_id` LIKE ?";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("ss", $userID, $videoID);
        $preparedStatement->execute();

        $result = $preparedStatement->get_result();

        return $result->fetch_object();
    }
}

if (!function_exists('insertView')) {
    function insertView($userID, $videoID)
    {
        $connection = getConnection();

        $query = "INSERT INTO `view_detail` (`user_id`, `video_id`) VALUES (?, ?)";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("ss", $userID, $videoID);
        $preparedStatement->execute();
    }
}

