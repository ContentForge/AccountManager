<?php


namespace dragonestia\command\module;


use dragonestia\Database;
use dragonestia\Dragonestia;
use pocketmine\command\CommandSender;

class ExcludeModule extends AdminModule
{

    public function getName(): string
    {
        return "exclude";
    }

    public function getDescription(): string
    {
        return "Забрать права администратора";
    }

    public function getUsage(): string
    {
        return "/admin exclude <НикНейм>";
    }

    protected function execute(CommandSender $sender, array $args): void
    {
        if(empty($args)){
            $this->sendUsage($sender);
            return;
        }

        $target = $args[0];

        if(!Dragonestia::getUserManager()->isRegistered($target)){
            $sender->sendMessage("§cПользователь не найден.");
            return;
        }

        Database::getInstance()->update("UPDATE players SET is_admin = false WHERE nickname = ':name' LIMIT 1;", ['name' => $target]);
        $sender->sendMessage("§eПрава администратора были успешно отозваны у пользователя §l{$target}§r§e.");

        $player = $sender->getServer()->getPlayerExact($target);
        $this->logger->warning("{$sender->getName()} снял права администратора у пользователя $target");
        if($player == null) return;
        Dragonestia::getUserManager()->getUser($player)->setAdmin(false);
    }

}