<?php


namespace dragonestia\event\transaction;


use pocketmine\event\Cancellable;

class TransactionSendEvent extends TransactionEvent implements Cancellable
{

    private ?string $errorMessage = null;

    public function setErrorMessage(?string $errorMessage = null): void
    {
        $this->errorMessage = $errorMessage;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

}