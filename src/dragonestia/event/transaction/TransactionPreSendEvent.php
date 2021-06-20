<?php


namespace dragonestia\event\transaction;


class TransactionPreSendEvent extends TransactionEvent
{

    private float $sale = 0;

    public function getSale()
    {
        return $this->sale;
    }

    public function setSale(float $sale): void
    {
        $this->sale = $sale;
    }

}