<?php

namespace App\Types;

class DonorMediaTypes
{
    const ALL_TYPES = ['pending', 'approved', 'reproved'];
    const ALL = 'all';
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const REPROVED = 'reproved';
    const DEFAULT_PER_PAGE = 15;
    const MIN_QUANTITY_PHOTOS = 1;
    const MAX_QUANTITY_PHOTOS = 20;
    const CONFIG_ENABLED = 'enabled';
}
