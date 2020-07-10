<?php

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

