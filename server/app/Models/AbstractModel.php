<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model
{
    protected $keyType = 'string';
    protected $dateFormat = 'Y-m-d H:i:sO';
}
