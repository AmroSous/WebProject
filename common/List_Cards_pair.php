<?php

class List_Cards_pair
{
    public $list = NULL;
    public $cards = NULL;

    public function __construct($list, $cards){
        $this->cards = $cards;
        $this->list = $list;
    }
}