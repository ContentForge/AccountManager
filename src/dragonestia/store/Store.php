<?php


namespace dragonestia\store;


use form\SimpleForm;
use pocketmine\Player;

class Store
{

    private static ?Store $instance = null;
    private array $pages = [];
    private array $products = [];

    private function __construct()
    {

    }

    public function addProduct(Product $product): void
    {
        if(!$product instanceof ContainsInStore){
            return;
        }
        $this->products[$product->getPageId()][] = $product;
    }

    public function addPage(Page $page): void
    {
        $this->pages[] = $page;
    }

    public function getPageById(?string $id): ?Page
    {
        if($id === null) return null;
        foreach ($this->pages as $page){
            if($page->getId() === $id) return $page;
        }
        return null;
    }

    public function getPageProducts(Page $page): array
    {
        if(!isset($this->products[$page->getId()])) return array();
        return $this->products[$page->getId()];
    }

    public function sendToPlayer(Player $player): void
    {
        $form = new SimpleForm(function(Player $player, ?int $data){
            if($data === null) return;

            $this->pages[$data]->sendToPlayer($player);
        });
        $form->setTitle("Магазин");
        $form->setContent("");
        foreach ($this->pages as $page) $form->addButton($page->getName(), SimpleForm::IMAGE_TYPE_PATH, $page->getIcon());
        $player->sendForm($form);
    }

    public static function getInstance(): ?Store
    {
        if(self::$instance === null) self::$instance = new Store();
        return self::$instance;
    }

}