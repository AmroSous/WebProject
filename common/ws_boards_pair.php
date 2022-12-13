<?php

class ws_boards_pair
{
    public $workspace = NULL;
    public $boards = NULL;

    public function __construct($workspace, $boards){
        $this->workspace = $workspace;
        $this->boards = $boards;
    }
}