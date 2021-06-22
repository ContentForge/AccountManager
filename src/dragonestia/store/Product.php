<?php


namespace dragonestia\store;


use dragonestia\Database;
use dragonestia\Dragonestia;
use dragonestia\event\transaction\TransactionPayedEvent;
use dragonestia\event\transaction\TransactionSendEvent;
use dragonestia\user\User;
use form\ModalForm;
use pocketmine\Player;

abstract class Product
{

    private int $price;
    private float $sale;

    public function __construct(int $price, float $sale)
    {
        $this->price = $price;
        $this->sale = $sale;
    }

    public abstract function getName(): string;

    public abstract function getDescription(): string;

    public function canOpen(Player $player, User $user): bool
    {
        return true;
    }

    protected abstract function execute(Player $player, User $user);

    public final function sendToPlayer(Player $player, ?float $sale = null): void
    {
        if($sale == null) $sale = $this->sale;
        $user = Dragonestia::getUserManager()->getUser($player);
        $finalPrice = (int) ($this->price * (1 - $sale));

        $e = new TransactionSendEvent($player, $user, $this);
        $e->call();
        if($e->isCancelled() || !$this->canOpen($player, $user)){
            if($e->getErrorMessage() != null) $player->sendMessage($e->getErrorMessage());
            return;
        }

        $form = new ModalForm(function(Player $player, ?bool $data) use ($user, $finalPrice) {
            if($data === null || !$data){
                if($this instanceof ContainsInStore){
                    $page = Store::getInstance()->getPageById($this->getPageId());
                    if($page === null) return;
                    $page->sendToPlayer($player);
                }
                return;
            }
            $shards = $user->getShards();

            if($shards < $finalPrice){
                $player->sendMessage("§cНедостаточно средств для покупки данного товара.");
                return;
            }

            $db = Database::getInstance();
            $db->update("UPDATE players SET shards = shards - $finalPrice WHERE id = {$user->getId()} LIMIT 1;");
            $db->update("INSERT INTO transactions (user_id, price, final_price, product_name) VALUES ({$user->getId()}, $this->price, $finalPrice, ':product');", ['product', $this->getName()]);
            $shards = $user->getShards();

            if($this instanceof ThirdPartyProduct){
                $income = (int) ($finalPrice * $this->getIncome());
                $db->update("UPDATE players SET shards = shards + $income WHERE id = {$this->getOwnerUserId()} LIMIT 1;");
                $db->update("INSERT INTO tp_incomes (user_id, income, product_name, owner_id) VALUES ({$user->getId()}, $income, ':product', {$this->getOwnerUserId()});", ['product', $this->getName()]);
            }

            $this->execute($player, $user);

            $e = new TransactionPayedEvent($player, $user, $this, $finalPrice);
            $e->call();

            $player->sendMessage("§bВы успешно приобрели §l'{$this->getName()}'§r§b за §l$finalPrice рублей§r§b. У вас на счету осталось еще §l{$shards} рублей§r§e.");
        });

        $form->setTitle("Оплата товара");
        $form->setContent(
            "Название товара: §2§l{$this->getName()}§r\n".
            "Ваш баланс: §b§l{$user->getCashedShards()} рублей§r\n".
            ($this instanceof ThirdPartyProduct? "Продавец: §g§l{$this->getOwnerName()}§r\n" : "").
            "Цена товара: §b§l$finalPrice рублей§r\n\n".
            "Описание: §3{$this->getDescription()}§r".
            ($this instanceof ThirdPartyProduct? "\n\n§7§oПродавец получает определенный процент с продаж данного товара.§r" : "")
        );
        $form->setPositiveButton("§lКупить за $finalPrice рублей");
        $form->setNegativeButton("Отказаться от покупки");
        $player->sendForm($form);
    }

}