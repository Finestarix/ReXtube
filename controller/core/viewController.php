<?php

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

