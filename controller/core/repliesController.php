<?php

require_once(dirname(__FILE__) . '/databaseController.php');

if (!function_exists('getRepliesByCommentID')) {
    function getRepliesByCommentID($commentID)
    {
        $connection = getConnection();

        $query = "SELECT * FROM `replies` WHERE `comment_id` = ? ORDER BY `date` DESC";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("s", $commentID);
        $preparedStatement->execute();

        return $preparedStatement->get_result();
    }
}

if (!function_exists('insertReply')) {
    function insertReply($reply)
    {
        $connection = getConnection();

        $query = "INSERT INTO `replies` (`id`, `comment_id`, `user_id`, `text`, `date`) VALUES (?, ?, ?, ?, ?)";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("sssss", $reply->id, $reply->comment_id, $reply->user_id,
            $reply->text, $reply->date);
        $preparedStatement->execute();
    }
}

