<?php

class User
{
    public $userID;
    public $userName;
    public $userEmail;
    public $userImage;

    public function __construct($userID, $userName, $userEmail, $userImage)
    {
        $this->userID = $userID;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->userImage = $userImage;
    }

}