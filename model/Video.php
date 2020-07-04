<?php

class Video
{
    public $videoID;
    public $userID;
    public $videoTitle;
    public $videoDescription;
    public $videoDate;

    public function __construct($videoID, $userID, $videoTitle, $videoDescription, $videoDate)
    {
        $this->videoID = $videoID;
        $this->userID = $userID;
        $this->videoTitle = $videoTitle;
        $this->videoDescription = $videoDescription;
        $this->videoDate = $videoDate;
    }
}