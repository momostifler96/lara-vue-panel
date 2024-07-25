<?php

namespace LVP\Enums;

enum ModelRelationType: string
{

    case BelongsTo = 'belongsTo';
    case HasMany = 'hasMany';
    case HasOne = 'hasOne';
    case HasManyThrough = 'hasManyThrough';

    public function value(): string
    {
        return $this->value;
    }
}
