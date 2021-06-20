<?php


namespace dragonestia\store;


interface ContainsInStore
{

    public function getPageId(): string;

    public function canShowPriceOnStore(): bool;

    public function getIcon(): string;

}