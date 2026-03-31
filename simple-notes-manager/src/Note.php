<?php

declare(strict_types=1);

class Note
{
    public int $id;
    public bool $isPinned = true;
    public string $createdAt;

    public function __construct(
        public string $title,
        public string $content = '',
        public array $tags = [],
    ) {
        $this->id = 0;
        $this->createdAt = date('Y-m-d H:i:s');
    }

    public function pin(): void
    {
        $this->isPinned = true;
    }

    public function unpin(): void
    {
        $this->isPinned = false;
    }

    public function __toString(): string
    {
        return "#{$this->id} {$this->title} [{$this->content}] <i>{$this->createdAt}</i>";
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
