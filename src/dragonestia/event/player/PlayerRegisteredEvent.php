<?php


namespace dragonestia\event\player;


use pocketmine\event\player\PlayerEvent;
use pocketmine\Player;

class PlayerRegisteredEvent extends PlayerEvent
{

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

}