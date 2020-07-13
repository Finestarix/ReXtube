<?php
require_once(dirname(__FILE__) . '/databaseController.php');
require_once(dirname(__FILE__) . '/../../util/uriHelper.php');

checkURI(realpath(__FILE__));

if (!function_exists('getTotalLikeByVideoID')) {
    function getTotalLikeByVideoID($videoID)
    {
        $connection = getConnection();

        $query = "SELECT COUNT(*) AS totalLike FROM `like_detail` WHERE `video_id` = ?";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("s", $videoID);
        $preparedStatement->execute();

        $result = $preparedStatement->get_result();

        return $result->fetch_object();
    }
}

if (!function_exists('isUserLike')) {
    function isUserLike($userID, $videoID)
    {
        $connection = getConnection();

        $query = "SELECT * FROM `like_detail` WHERE `user_id` LIKE ? AND `video_id` LIKE ?";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("ss", $userID, $videoID);
        $preparedStatement->execute();

        $result = $preparedStatement->get_result();

        return $result->fetch_object();
    }
}

if (!function_exists('insertLike')) {
    function insertLike($userID, $videoID)
    {
        $connection = getConnection();

        $query = "INSERT INTO `like_detail` (`user_id`, `video_id`) VALUES (?, ?)";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("ss", $userID, $videoID);
        $preparedStatement->execute();
    }
}

if (!function_exists('removeLike')) {
    function removeLike($userID, $videoID)
    {
        $connection = getConnection();

        $query = "DELETE FROM `like_detail` WHERE `user_id` LIKE ? AND `video_id` LIKE ?";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("ss", $userID, $videoID);
        $preparedStatement->execute();
    }
}
