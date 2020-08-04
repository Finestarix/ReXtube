<?php
require_once(dirname(__FILE__) . '/databaseController.php');
require_once(dirname(__FILE__) . '/../../util/uriHelper.php');

checkURI(realpath(__FILE__));

if (!function_exists('getHistoriesByUserID')) {
    function getHistoriesByUserID($userID)
    {
        $connection = getConnection();

        $query = "SELECT * FROM `histories` WHERE `user_id` = ? ORDER BY `date` DESC";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("s", $userID);
        $preparedStatement->execute();

        return $preparedStatement->get_result();
    }
}

if (!function_exists('isUserHistory')) {
    function isUserHistory($userID, $videoID)
    {
        $connection = getConnection();

        $query = "SELECT * FROM `histories` WHERE `user_id` LIKE ? AND `video_id` LIKE ?";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("ss", $userID, $videoID);
        $preparedStatement->execute();

        $result = $preparedStatement->get_result();

        return $result->fetch_object();
    }
}

if (!function_exists('insertHistory')) {
    function insertHistory($history)
    {
        $connection = getConnection();

        $query = "INSERT INTO `histories` (`id`, `user_id`, `video_id`, `date`) VALUES (?, ?, ?, ?)";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("ssss", $history->id, $history->user_id,
            $history->video_id, $history->date);
        $preparedStatement->execute();
    }
}