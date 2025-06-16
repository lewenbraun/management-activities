<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ActivityTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivityType extends Model
{
    /** @use HasFactory<ActivityTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'icon_name',
        'display_order',
    ];

    /**
     * @return HasMany<Activity, $this>
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
