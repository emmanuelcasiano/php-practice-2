<?php

declare(strict_types=1);

require_once __DIR__ . '/Note.php';

class NoteManager
{
    public array $notes = [];

    public function __construct(array $notes = [])
    {
        $this->notes = $notes;
    }

    public function addNote(Note $note): static
    {
        $note->setId(count($this->notes) + 1);
        $this->notes[$note->id] = $note;
        return $this;
    }

    public function deleteNote(int $id): void
    {
        unset($this->notes[$id]);
    }

    public function updateNote(int $id, string $title, string $content): void
    {
        if (isset($this->notes[$id])) {
            $this->notes[$id]->title = $title;
            $this->notes[$id]->content = $content;
        }
    }

    public function getNote(int $id): ?Note
    {
        return $this->notes[$id] ?? null;
    }

    public function getNotes(): array
    {
        return $this->notes;
    }
}
