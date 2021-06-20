<?php


namespace dragonestia\event\user;


use dragonestia\user\User;
use pocketmine\event\player\PlayerEvent;
use pocketmine\Player;

class UserQuitEvent extends UserEvent
{

    private int $sessionTime;

    public function __construct(Player $player, User $user)
    {
        parent::__construct($player, $user);
        $this->sessionTime = time() - $user->joinTime;
    }

    public function getSessionTime(): int
    {
        return $this->sessionTime;
    }

}