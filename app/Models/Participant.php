<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ParticipantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Participant extends Model
{
    /** @use HasFactory<ParticipantFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'website_link',
        'logo_path',
        'location_description',
        'coordinates',
    ];

    protected $casts = [
        'coordinates' => 'array',
    ];

    /**
     * @return BelongsToMany<Activity, $this, Pivot>
     */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class);
    }
}
