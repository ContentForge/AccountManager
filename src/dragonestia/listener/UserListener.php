<?php


namespace dragonestia\listener;


use dragonestia\Database;
use dragonestia\event\user\UserLoginEvent;
use dragonestia\event\user\UserQuitEvent;
use dragonestia\event\player\PlayerRegisteredEvent;
use dragonestia\user\UserManager;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;

class UserListener implements Listener
{

    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    function onPreLogin(PlayerPreLoginEvent $event)
    {
        $player = $event->getPlayer();

        if(strlen($player->getName()) < 32) return;

        $event->setKickMessage("Недопустимый никнейм");
        $event->setCancelled(true);
    }

    /**
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    function onLogin(PlayerLoginEvent $event)
    {
        $player = $event->getPlayer();

        if(!$this->userManager->isRegistered($player->getName())){
            $this->userManager->register($player->getName());
            $e = new PlayerRegisteredEvent($player);
            $e->call();
        }

        $user = $this->userManager->load($player);
        $e = new UserLoginEvent($player, $user);
        $e->call();
    }

    /**
     * @priority LOWEST
     */
    function onQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();

        $user = $this->userManager->unload($player);
        $e = new UserQuitEvent($player, $user);
        $e->call();
    }

}