<?php

class Subscriber
{
    public $userID;
    public $friendID;

    public function __construct($userID, $friendID)
    {
        $this->userID = $userID;
        $this->friendID = $friendID;
    }


}