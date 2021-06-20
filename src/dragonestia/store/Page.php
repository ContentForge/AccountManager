<?php


namespace dragonestia\store;


use dragonestia\Dragonestia;
use dragonestia\event\transaction\TransactionPreSendEvent;
use form\SimpleForm;
use pocketmine\Player;

class Page
{

    private string $id;
    private string $name;
    private string $text;
    private string $icon;

    public function __construct(string $id, string $name, string $text = "", string $icon = "")
    {
        $this->id = $id;
        $this->name = $name;
        $this->text = $text;
        $this->icon = $icon;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    public function sendToPlayer(Player $player): void
    {
        $products = Store::getInstance()->getPageProducts($this);
        $form = new SimpleForm(function (Player $player, ?int $data) use ($products) {
            if($data === null) return;

            $transaction = $products[$data];
            $e = new TransactionPreSendEvent($player, Dragonestia::getUserManager()->getUser($player), $transaction);
            $e->call();
            $transaction->sendToPlayer($player, $e->getSale());
        });
        $form->setTitle($this->getName());
        $form->setContent($this->getText());
        foreach ($products as $product){
            $form->addButton($product->getName(), SimpleForm::IMAGE_TYPE_PATH, $product->getIcon());
        }
        $player->sendForm($form);
    }

}