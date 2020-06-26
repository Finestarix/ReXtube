<?php

class User
{
    public $userID;
    public $userName;
    public $userEmail;
    public $userProfile;

    public function __construct($userID, $userName, $userEmail, $userProfile)
    {
        $this->userID = $userID;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->userProfile = $userProfile;
    }

}