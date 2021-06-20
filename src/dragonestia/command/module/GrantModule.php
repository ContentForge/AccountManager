<?php


namespace dragonestia\command\module;


use dragonestia\Database;
use dragonestia\Dragonestia;
use pocketmine\command\CommandSender;

class GrantModule extends AdminModule
{

    public function getName(): string
    {
        return "grant";
    }

    public function getDescription(): string
    {
        return "Выдача прав администратора";
    }

    public function getUsage(): string
    {
        return "/admin grant <НикНейм>";
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

        Database::getInstance()->update("UPDATE players SET is_admin = true WHERE nickname = ':name' LIMIT 1;", ['name' => $target]);
        $sender->sendMessage("§eПрава администратора были успешно выданы пользователю §l{$target}§r§e.");

        $player = $sender->getServer()->getPlayerExact($target);
        $this->logger->warning("{$sender->getName()} выдал права администратора пользователю $target");
        if($player == null) return;
        Dragonestia::getUserManager()->getUser($player)->setAdmin(true);
        $player->sendMessage("§eВы получили права администратора.");
    }

}