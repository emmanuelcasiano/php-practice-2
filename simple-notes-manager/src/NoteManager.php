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
        $this->notes[] = $note;
        return $this;
    }

    public function getNotes(): array
    {
        return $this->notes;
    }
}
