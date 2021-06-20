<?php


namespace dragonestia\store;


interface ThirdPartyProduct
{

    public function getOwnerUserId(): int;

    public function getOwnerName(): string;

    public function getIncome(): float;

}