<?php

namespace App\Models\System;

use App\Traits\Uuid;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use Uuid;
}