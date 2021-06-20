<?php


namespace dragonestia\event\transaction;


use dragonestia\store\Product;
use dragonestia\user\User;
use pocketmine\Player;

class TransactionPayedEvent extends TransactionEvent
{

    private int $price;

    public function __construct(Player $player, User $user, Product $transaction, int $price)
    {
        parent::__construct($player, $user, $transaction);
        $this->price = $price;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

}