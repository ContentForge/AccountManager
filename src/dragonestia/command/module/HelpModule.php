<?php


namespace dragonestia\command\module;


use dragonestia\command\AdminCommand;
use pocketmine\command\CommandSender;

class HelpModule extends AdminModule
{

    public function getName(): string
    {
        return "help";
    }

    public function getDescription(): string
    {
        return "Список всех суб-команд";
    }

    protected function execute(CommandSender $sender, array $args): void
    {
        $sender->sendMessage("§l-------[§eHELP§f]-------");
        foreach (AdminCommand::$modules as $module){
            $sender->sendMessage("§l§2/admin {$module->getName()}§r - §7{$module->getDescription()}");
        }
        $sender->sendMessage("§l-------======-------");
    }

}