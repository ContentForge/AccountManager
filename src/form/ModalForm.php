<?php

declare(strict_types = 1);

namespace form;

class ModalForm extends Form {

    /** @var string */
    private string $content = "";

    /**
     * @param callable|null $callable
     */
    public function __construct(?callable $callable) {
        parent::__construct($callable);
        $this->data["type"] = "modal";
        $this->data["title"] = "";
        $this->data["content"] = $this->content;
        $this->data["button1"] = "";
        $this->data["button2"] = "";
    }

    /**
     * @param string $title
     * @return ModalForm
     */
    public function setTitle(string $title) : ModalForm {
        $this->data["title"] = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle() : string {
        return $this->data["title"];
    }

    /**
     * @return string
     */
    public function getContent() : string {
        return $this->data["content"];
    }

    /**
     * @param string $content
     * @return ModalForm
     */
    public function setContent(string $content) : ModalForm {
        $this->data["content"] = $content;

        return $this;
    }

    /**
     * @param string $text
     * @return ModalForm
     */
    public function setPositiveButton(string $text) : ModalForm {
        $this->data["button1"] = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getPositiveButton() : string {
        return $this->data["button1"];
    }

    /**
     * @param string $text
     * @return ModalForm
     */
    public function setNegativeButton(string $text) : ModalForm {
        $this->data["button2"] = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getNegativeButton() : string {
        return $this->data["button2"];
    }
}
