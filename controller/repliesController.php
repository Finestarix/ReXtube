<?php

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

