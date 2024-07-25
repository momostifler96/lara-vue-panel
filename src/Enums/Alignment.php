<?php

namespace LVP\Enums;

enum Alignment: string
{

    case LEFT = 'left';
    case RIGHT = 'right';
    case CENTER = 'center';

    public function label(): string
    {
        return match ($this) {
            Alignment::LEFT => 'Left',
            Alignment::RIGHT => 'Right',
            Alignment::CENTER => 'Center',
        };
    }
}
