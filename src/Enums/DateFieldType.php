<?php

namespace LVP\Enums;

enum DateFieldType: string
{

    case DATE = 'date';
    case DATETIME = 'date-time';
    case TIME = 'time';
    case DAY = 'day';
    case MONTH = 'month';
    case MONTH_OF_YEAR = 'month-year';
    case YEAR = 'year';
}
