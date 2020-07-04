<?php
require_once(dirname(__FILE__) . '/../model/Video.php');
require_once(dirname(__FILE__) . '/databaseController.php');

if (!function_exists('getVideoByUserID')) {
    function getVideoByUserID($userID)
    {
        $connection = getConnection();

        $query = "SELECT * FROM `videos` WHERE `user_id` LIKE ? ORDER BY `date` DESC";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("s", $userID);
        $preparedStatement->execute();

        return $preparedStatement->get_result();
    }
}

if (!function_exists('getAllVideo')) {
    function getAllVideo()
    {
        $connection = getConnection();

        $query = "SELECT * FROM `videos` ORDER BY `date` DESC";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->execute();

        return $preparedStatement->get_result();
    }
}

if (!function_exists('insertVideo')) {
    function insertVideo($video)
    {
        $connection = getConnection();

        $query = "INSERT INTO `videos`(`id`, `user_id`, `title`, `description`, `date`) VALUES (?, ?, ?, ?, ?)";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("sssss", $video->videoID, $video->userID, $video->videoTitle,
            $video->videoDescription, $video->videoDate);
        $preparedStatement->execute();
    }
}