<?php

declare(strict_types=1);

namespace Reservation\Domain;

final class Note
{
    private string $note;

    private function __construct(string $note)
    {
        $this->note = $note;
    }

    public static function fromString(string $note): Note
    {
        return new self($note);
    }

    public function toString(): string
    {
        return $this->note;
    }

    public function equals(self $note): bool
    {
        return $this->note === $note->note;
    }
}
