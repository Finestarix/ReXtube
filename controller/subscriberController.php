<?php
require_once(dirname(__FILE__) . '/../model/Subscriber.php');
require_once(dirname(__FILE__) . '/databaseController.php');

if (!function_exists('getTotalUserSubscriber')) {
    function getTotalUserSubscriber($userID)
    {
        $connection = getConnection();

        $query = "SELECT COUNT(*) AS Total FROM `subscribers` WHERE `user_id` LIKE ?";

        $preparedStatement = $connection->prepare($query);
        $preparedStatement->bind_param("s", $userID);
        $preparedStatement->execute();

        $result = $preparedStatement->get_result();

        return $result->fetch_object();
    }
}