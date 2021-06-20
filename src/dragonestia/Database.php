<?php


namespace dragonestia;


use mysqli;
use pocketmine\plugin\PluginLogger;

final class Database
{

    private static ?Database $instance = null;
    private PluginLogger $logger;
    private mysqli $driver;

    private function __construct(PluginLogger $logger, string $host, string $user, string $password, string $base)
    {
        $this->logger = $logger;
        $this->logger->info("Инициализация базы данных");

        $this->driver = new mysqli("p:$host", $user, $password, $base);
        self::$instance = $this;

        $this->logger->info("Инициализация базы данных успешно завершена");

        //Создание таблиц
        $this->update("CREATE TABLE IF NOT EXISTS players (
            id int(5) not null unique AUTO_INCREMENT,
            nickname varchar(32) not null unique,
            password varchar(32) default null,
            shards int(5) not null default 0, 
            icon tinytext not null default 'default',
            is_admin boolean not null default false,
            primary key(id)
        );");

        $this->update("CREATE TABLE IF NOT EXISTS transactions (
            id int(5) not null unique AUTO_INCREMENT,
            user_id int(5) not null,
            price int not null,
            final_price int not null,
            product_name tinytext not null,
            primary key(id)
        );");

        $this->update("CREATE TABLE IF NOT EXISTS tp_incomes (
            id int(5) not null unique AUTO_INCREMENT,
            user_id int(5) not null,
            income int not null,
            product_name tinytext not null,
            owner_id int(5) not null,
            primary key(id)
        );");
    }

    public function update(string $sql, array $params = []): void {
        $sql = $this->stringSubstitution($sql, $params);
        $this->driver->query($sql);
    }

    public function query(string $sql, array $params = [], bool $one = true): ?array {
        $sql = $this->stringSubstitution($sql, $params);
        $result = $this->driver->query($sql);
        if(is_bool($result)) return null;
        $result = $result->fetch_all(1);

        if(!$one) return $result;
        return empty($result)? null : $result[0];
    }

    private function stringSubstitution(string $sql, array $params): string
    {
        foreach ($params as $key => $value){
            $sql = str_replace(":$key", $this->driver->real_escape_string($value), $sql);
        }
        return $sql;
    }

    public function close(): void
    {
        self::$instance = null;
        $this->driver->close();
        $this->logger->warning("Отключение от базы данных");
    }

    public static function init(PluginLogger $logger, string $host, string $user, string $password, string $base)
    {
        if(self::$instance != null) return;
        self::$instance = new Database($logger, $host, $user, $password, $base);
    }

    public static function getInstance(): Database
    {
        return self::$instance;
    }

}