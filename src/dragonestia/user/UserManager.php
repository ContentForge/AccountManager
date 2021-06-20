<?php


namespace dragonestia\user;


use dragonestia\Database;
use dragonestia\Dragonestia;
use pocketmine\Player;

final class UserManager
{

    private Dragonestia $main;
    private array $users;
    private array $initsData = [];
    private array $saversData = [];

    public function __construct(Dragonestia $main)
    {
        $this->main = $main;
        $this->users = array();
    }

    public function getMain(): Dragonestia
    {
        return $this->main;
    }

    public function load(Player $player): User
    {
        $user = new User($player, $this->initsData);
        $this->users[$player->getId()] = $user;
        return $user;
    }

    public function unload(Player $player): User
    {
        $user = $this->users[$player->getId()];
        unset($this->users[$player->getId()]);
        $user->unload($this->saversData);
        return $user;
    }

    public function unloadAll()
    {
        foreach ($this->users as $user){
            $user->unload($this->saversData);
        }
        $this->users = [];
    }

    public function getUser(Player $player): User
    {
        return $this->users[$player->getId()];
    }

    public function isRegistered(string $playerName): bool
    {
        return (int) Database::getInstance()->query("SELECT COUNT(*) as count FROM players WHERE nickname = ':name';", ['name' => $playerName])['count'] !== 0;
    }

    public function register(string $nickname): void
    {
        Database::getInstance()->update("INSERT INTO players (nickname) VALUES (':nickname');", ['nickname' => $nickname]);
    }

    public function registerOtherParam(callable $init, callable $saver)
    {
        $this->initsData[] = $init;
        $this->saversData[] = $saver;
    }

}