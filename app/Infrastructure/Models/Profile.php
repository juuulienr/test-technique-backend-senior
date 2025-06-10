<?php

namespace App\Infrastructure\Models;

use App\Domain\ValueObjects\ProfileStatut;
use Database\Factories\ProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Infrastructure\Models\Admin;
use App\Infrastructure\Models\Comment;

/**
 * @property int $id
 * @property string $nom
 * @property string $prenom
 * @property string $image
 * @property ProfileStatut $statut
 * @property int $admin_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\ProfileFactory factory($count = null, $state = [])
 */
class Profile extends Model
{
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory;

    protected $fillable = [
      'nom',
      'prenom',
      'image',
      'statut',
      'admin_id',
    ];

    protected $casts = [
      'statut' => ProfileStatut::class,
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ProfileFactory
    {
        return ProfileFactory::new();
    }

    /**
     * @return BelongsTo<\App\Infrastructure\Models\Admin, \App\Infrastructure\Models\Profile>
     */
    public function admin(): BelongsTo
    {
        /** @var BelongsTo<\App\Infrastructure\Models\Admin, \App\Infrastructure\Models\Profile> $relation */
        $relation = $this->belongsTo(Admin::class);

        return $relation;
    }

    /**
     * @return HasMany<Comment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
