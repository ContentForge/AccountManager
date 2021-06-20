<?php


namespace dragonestia\event\user;


use dragonestia\user\User;
use pocketmine\event\player\PlayerEvent;
use pocketmine\Player;

abstract class UserEvent extends PlayerEvent
{

    protected User $user;

    public function __construct(Player $player, User $user)
    {
        $this->user = $user;
        $this->player = $player;
    }

    public function getUser(): User
    {
        return $this->user;
    }

}