<?php


namespace dragonestia\task;


use dragonestia\Database;
use pocketmine\scheduler\Task;

class DatabaseSaverConnectionTask extends Task
{

    public function onRun(int $currentTick)
    {
        //Это костыль для поддержания постоянного подключения к бд.
        //Не советуется убирать, тк подключение может сбросится, если долго не отправлялись запросы.
        Database::getInstance()->query("SHOW TABLES;");
    }

}