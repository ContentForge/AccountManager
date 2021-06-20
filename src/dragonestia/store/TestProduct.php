<?php


namespace dragonestia\store;


use dragonestia\user\User;
use pocketmine\math\Vector3;
use pocketmine\Player;

class TestProduct extends Product
{

    public function getName(): string
    {
        return "Тестовый товар";
    }

    public function getDescription(): string
    {
        return "Данный товар предназначен для тестирования магазина и ничего большего.";
    }

    protected function execute(Player $player, User $user)
    {
        $player->setMotion(new Vector3(0, 5, 0));
    }

}