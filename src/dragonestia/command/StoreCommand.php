<?php


namespace dragonestia\command;


use dragonestia\store\Store;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class StoreCommand extends Command
{

    public function __construct()
    {
        parent::__construct("store", "Магазин", "/store", ["shop"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if($sender instanceof Player){
            Store::getInstance()->sendToPlayer($sender);
            return true;
        }

        $sender->sendMessage("Данную команду можно вводить только в игре.");
        return false;
    }

}