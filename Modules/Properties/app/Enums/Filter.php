<?php

namespace Modules\Properties\app\Enums;

enum Filter: string
{
    case LOCATION = 'location';
    case TYPE = 'type';
    case ROOMS = 'rooms';
    case BATHROOMS = 'bathrooms';
    case MIN_PRICE = 'min_price';
    case MAX_PRICE = 'max_price';
    case DEFAULT_SORT_BY = 'price';
    case DEFAULT_SORT_DIRECTION = 'asc';
}
