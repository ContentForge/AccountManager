<?php


namespace dragonestia\command;


use dragonestia\command\module\ExcludeModule;
use dragonestia\command\module\GrantModule;
use dragonestia\command\module\HelpModule;
use dragonestia\command\module\ShardsModule;
use dragonestia\command\module\TestProductModule;
use dragonestia\Dragonestia;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginLogger;

final class AdminCommand extends Command
{

    public static array $modules = [];
    private static PluginLogger $logger;

    public function __construct(PluginLogger $logger)
    {
        parent::__construct("admin", "Управление DragonestiaCore", "/admin", ['dragonestia']);

        self::$logger = $logger;
        $this->initDefaultModules();
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if($sender instanceof Player){
            $user = Dragonestia::getUserManager()->getUser($sender);
            $access = $user->isAdmin();
        } else $access = true;

        if(!$access){
            $sender->sendMessage("§cДоступ к данной команде имеет только доверенный пользователь.");
            return false;
        }

        if(empty($args)){
            $sender->sendMessage("§c§lDragonestia§7Core§f bу §bqPexLegendary§f(§9github.com/qPexLegendary§f).");
            $sender->sendMessage("§eДля просмотра списка команд введите §l/admin help§r§e.");
            return true;
        }

        $moduleName = strtolower(array_shift($args));
        if(isset(self::$modules[$moduleName])){
            $module = self::$modules[$moduleName];
            $module->send($sender, $args);
            return true;
        }

        $sender->sendMessage("§cВведена неверная суб-команда.");
        return false;
    }

    private function initDefaultModules(): void
    {
        self::addModule(HelpModule::class);
        self::addModule(GrantModule::class);
        self::addModule(ExcludeModule::class);
        self::addModule(ShardsModule::class);
        self::addModule(TestProductModule::class);
    }

    public static function addModule(string $moduleClass): void
    {
        $module = new $moduleClass(self::$logger);
        self::$modules[strtolower($module->getName())] = $module;
    }

}