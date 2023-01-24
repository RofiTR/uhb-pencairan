<?php

namespace App\Models\System;

use App\Traits\Uuid;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use Uuid;
}