<?php

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

