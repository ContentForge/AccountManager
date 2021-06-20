<?php


namespace dragonestia\user;


use dragonestia\Database;
use pocketmine\Player;

class User
{

    private Player $player;
    private bool $admin;
    private int $id;
    private bool $passedPassword;
    private int $cashedShards;
    public int $joinTime;
    public array $data = [];

    public function __construct(Player $player, array $initsData)
    {
        $this->player = $player;
        $this->load($initsData);
    }

    private function load(array $initsData): void
    {
        $db = Database::getInstance();
        $data = $db->query("SELECT id, is_admin, shards, password IS NOT NULL as passed_password FROM players WHERE nickname = ':nickname' LIMIT 1;", ['nickname' => $this->player->getName()]);
        $this->admin = (bool) $data['is_admin'];
        $this->id = (int) $data['id'];
        $this->passedPassword = (bool) $data['passed_password'];
        $this->cashedShards = (int) $data['shards'];

        $this->joinTime = time();

        foreach ($initsData as $func) $func($this);
    }

    public function unload(array $saversData): void
    {
        foreach ($saversData as $func) $func($this);
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $value): void
    {
        $this->admin = $value;
    }

    public function isPassedPassword(): bool
    {
        return $this->passedPassword;
    }

    public function getCashedShards(): int
    {
        return $this->cashedShards;
    }

    public function setCashedShards(int $cashedShards): void
    {
        $this->cashedShards = $cashedShards;
    }

    public function getShards(): int
    {
        $shards = (int) Database::getInstance()->query("SELECT shards FROM players WHERE id = $this->id LIMIT 1;")['shards'];
        $this->cashedShards = $shards;
        return $shards;
    }

    public function addShards(int $count): void
    {
        Database::getInstance()->update("UPDATE players SET shards = shards + $count WHERE id = $this->id LIMIT 1;");
    }

}