<?php

namespace LVP\Enums;

enum ActionMenuType: string
{

    case INLINE = 'inline';
    case DROPDOWN = 'dropdown';

    public function label(): string
    {
        return match ($this) {
            ActionMenuType::INLINE => 'inline',
            ActionMenuType::DROPDOWN => 'dropdown',
        };
    }
}
