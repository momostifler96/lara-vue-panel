<?php

namespace LVP\Enums;

enum LVPAction: string
{

    case CREATE = 'create';
    case EDIT = 'edit';

    public function label(): string
    {
        return match ($this) {
            LVPAction::CREATE => 'create',
            LVPAction::EDIT => 'edit',
        };
    }
}
