<?php

namespace App\Data\Enums;

enum StatusEnum: string
{
    case PENDING_STATUS = 'peding';
    case DONE_STATUS    = 'done';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function color($type = 'text'): string
    {
        return (match ($this) {
            Self::PENDING_STATUS => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
            Self::DONE_STATUS    => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
        })[$type];
    }
}
