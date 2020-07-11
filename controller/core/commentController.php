<?php

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
