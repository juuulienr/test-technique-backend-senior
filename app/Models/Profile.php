<?php

namespace App\Models;

use App\Enums\ProfileStatut;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Admin;

/**
 * @property string $nom
 * @property string $prenom
 * @property string|null $image
 * @property ProfileStatut $statut
 * @property int $admin_id
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
   * @return BelongsTo<\App\Models\Admin, \App\Models\Profile>
   */
  public function admin(): BelongsTo
  {
    /** @var BelongsTo<\App\Models\Admin, \App\Models\Profile> $relation */
    $relation = $this->belongsTo(Admin::class);

    return $relation;
  }
}
