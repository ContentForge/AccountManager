<?php


namespace dragonestia\command\module;


use dragonestia\store\TestProduct;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class TestProductModule extends AdminModule
{

    public function getName(): string
    {
        return "test-store";
    }

    public function getDescription(): string
    {
        return "Тестирование магазина";
    }

    protected function execute(CommandSender $sender, array $args): void
    {
        if(!$sender instanceof Player){
            $sender->sendMessage("§cДанную команду можно вводить только в игре.");
            return;
        }
        $product = new TestProduct(15, 0.1);
        $product->sendToPlayer($sender);
    }

}