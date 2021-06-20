<?php


namespace dragonestia\command\module;


use dragonestia\Database;
use dragonestia\Dragonestia;
use pocketmine\command\CommandSender;

class ShardsModule extends AdminModule
{

    public function getName(): string
    {
        return "shards";
    }

    public function getDescription(): string
    {
        return "Выдать деньги игроку";
    }

    public function getUsage(): string
    {
        return "/admin shards <НикНей> <Кол-во>";
    }

    protected function execute(CommandSender $sender, array $args): void
    {
        if(count($args) < 2){
            $this->sendUsage($sender);
            return;
        }

        $target = $args[0];
        $shards = (int) $args[1];

        if(!Dragonestia::getUserManager()->isRegistered($target)){
            $sender->sendMessage("§cПользователь не найден.");
            return;
        }
        if($shards <= 0){
            $sender->sendMessage("§cВведено неверное количетсво.");
            return;
        }

        Database::getInstance()->update("UPDATE players SET shards = shards + $shards WHERE nickname = ':name' LIMIT 1;", ['name' => $target]);
        $sender->sendMessage("§eВы успешно зачислити §l$shards рублей§r§e игроку §l$target §r§eна счет.");
        $this->logger->info("{$sender->getName()} зачислил $shards рублей на счет игроку $target");

        $player = $sender->getServer()->getPlayerExact($target);
        if($player === null) return;
        $player->sendMessage("§eВам на счет было зачислено §l$shards рублей§r§e.");
    }

}