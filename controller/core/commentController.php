<?php

require_once(dirname(__FILE__) . '/databaseController.php');

if (!function_exists('getCommentByVideoID')) {
    function getCommentByVideoID($videoID)
    {
        $connection = getConnection();

        $query = "SELECT * FROM `comments` WHERE `video_id` = ? ORDER BY `date` DESC";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("s", $videoID);
        $preparedStatement->execute();

        return $preparedStatement->get_result();
    }
}

if (!function_exists('insertComment')) {
    function insertComment($comment)
    {
        $connection = getConnection();

        $query = "INSERT INTO `comments` (`id`, `video_id`, `user_id`, `text`, `date`) VALUES (?, ?, ?, ?, ?)";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("sssss", $comment->id, $comment->video_id, $comment->user_id,
            $comment->text, $comment->date);
        $preparedStatement->execute();
    }
}
