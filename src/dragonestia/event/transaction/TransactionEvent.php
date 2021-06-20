<?php


namespace dragonestia\event\transaction;


use dragonestia\store\Product;
use dragonestia\user\User;
use pocketmine\event\player\PlayerEvent;
use pocketmine\Player;

abstract class TransactionEvent extends PlayerEvent
{

    protected User $user;
    protected Product $transaction;

    public function __construct(Player $player, User $user, Product $transaction)
    {
        $this->player = $player;
        $this->user = $user;
        $this->transaction = $transaction;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getTransaction(): Product
    {
        return $this->transaction;
    }

}