<?php


namespace dragonestia;


use dragonestia\command\AdminCommand;
use dragonestia\command\StoreCommand;
use dragonestia\listener\UserListener;
use dragonestia\task\DatabaseSaverConnectionTask;
use dragonestia\user\UserManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Dragonestia extends PluginBase
{

    private static UserManager $userManager;

    function onLoad()
    {
        $DBConfig = new Config($this->getDataFolder()."database.properties", Config::PROPERTIES);
        $DBData = $DBConfig->getAll();
        if(!isset($DBData['host'])) $DBData['host'] = 'localhost';
        if(!isset($DBData['user'])) $DBData['user'] = 'root';
        if(!isset($DBData['password'])) $DBData['password'] = '';
        if(!isset($DBData['base'])) $DBData['base'] = 'test';
        $DBConfig->setAll($DBData);
        $DBConfig->save();

        Database::init($this->getLogger(), $DBData['host'], $DBData['user'], $DBData['password'], $DBData['base']);
        self::$userManager = new UserManager($this);
    }

    function onEnable()
    {
        $this->getScheduler()->scheduleRepeatingTask(new DatabaseSaverConnectionTask(), 20 * 60);

        $pluginManager = $this->getServer()->getPluginManager();
        $pluginManager->registerEvents(new UserListener(self::$userManager), $this);

        foreach ($this->getServer()->getOnlinePlayers() as $player){
            $player->close("", "Перезагрузка сервера");
        }

        $this->getServer()->getCommandMap()->registerAll("dragonestia", [
            new AdminCommand($this->getLogger()),
            new StoreCommand(),
        ]);
    }

    function onDisable()
    {
        Database::getInstance()->close();
        $this->getUserManager()->unloadAll();
    }

    public static function getUserManager(): UserManager
    {
        return self::$userManager;
    }

}