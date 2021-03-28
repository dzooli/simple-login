<?php

namespace Framework\Web;

class Response
{
    protected ?string $content = '';

    public function __construct(?string $content = null)
    {
        header('Cache-Control: max-age=30,no-store');
        $this->content = $content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content ?? '';
    }

    public function addHeader(?string $header = '')
    {
        if (empty($header)) {
            return;
        }
        header($header);
    }
}
