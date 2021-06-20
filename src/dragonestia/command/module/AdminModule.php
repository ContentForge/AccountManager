<?php


namespace dragonestia\command\module;


use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginLogger;

abstract class AdminModule
{

    protected PluginLogger $logger;

    public function __construct(PluginLogger $logger)
    {
        $this->logger = $logger;
    }

    public abstract function getName(): string;

    public abstract function getDescription(): string;

    public function getUsage(): string
    {
        return "/admin {$this->getName()}";
    }

    public final function send(CommandSender $sender, array $args): void
    {
        if($this instanceof InGameModule){
            if(!$sender instanceof Player){
                $sender->sendMessage("§cДанную команду можно вводить только в игре.");
                return;
            }
        }

        $this->execute($sender, $args);
    }

    public function sendUsage(CommandSender $sender): void
    {
        $sender->sendMessage("Использование: §7{$this->getUsage()}");
    }

    protected abstract function execute(CommandSender $sender, array $args): void;

}