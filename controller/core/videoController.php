<?php
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

if (!function_exists('getVideoByID')) {
    function getVideoByID($videoID)
    {
        $connection = getConnection();

        $query = "SELECT * FROM `videos` WHERE `id` LIKE ? ORDER BY `date` DESC";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("s", $videoID);
        $preparedStatement->execute();

        $result = $preparedStatement->get_result();

        return $result->fetch_object();
    }
}

if (!function_exists('getHomeVideo')) {
    function getHomeVideo()
    {
        $connection = getConnection();

        $query = "SELECT * FROM `videos` ORDER BY `date` DESC";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->execute();

        return $preparedStatement->get_result();
    }
}

if (!function_exists('getTrendingVideo')) {
    function getTrendingVideo()
    {
        $connection = getConnection();

        $query = "SELECT videos.id, videos.user_id, videos.title, videos.description, videos.date, viewData.totalView
                  FROM videos, ( SELECT videos.id, COUNT(*) AS totalView FROM videos JOIN view_detail 
                    WHERE view_detail.video_id = videos.id GROUP BY videos.id ) AS viewData
                  WHERE videos.id = viewData.id
                  ORDER BY viewData.totalView DESC";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->execute();

        return $preparedStatement->get_result();
    }
}

if (!function_exists('getRandomVideo')) {
    function getRandomVideo($videoID)
    {
        $connection = getConnection();

        $query = "SELECT * FROM `videos` WHERE `id` NOT LIKE ? ORDER BY RAND() DESC LIMIT 20";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("s", $videoID);
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
        $preparedStatement->bind_param("sssss", $video->id, $video->user_id, $video->title,
            $video->description, $video->date);
        $preparedStatement->execute();
    }
}