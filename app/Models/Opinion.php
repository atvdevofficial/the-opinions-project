<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Opinion extends Model
{
    use HasFactory;

    /**
     * Attributes to append
     */
    protected $appends = [
        'like_count',
        'liked_by_user'
    ];

    /**
     * Attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'critique_id',
        'text',
        'is_public'
    ];

    /**
     * Attributes that are hidden
     */
    protected $hidden = [
        'critique_id',
    ];

    /**
     * Attributes to cast into native types
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y/m/d H:i A',
        'updated_at' => 'datetime:Y/m/d H:i A',
    ];

    /**
     * Mutators and Accessors
     */

    /**
     * Like counter
     */
    public function getLikeCountAttribute()
    {
        return $this->likers()->count();
    }

    /**
     * Like counter
     */
    public function getLikedByUserAttribute()
    {
        // If user authenticated and has CRITIQUE role
        if (Auth::check() and Auth::user()->role == 'CRITIQUE') {
            $critiqueId = Auth::user()->critique->id;
            // If critique liked opinion
            if ($this->likers()->whereCritiqueId($critiqueId)->exists()) {
                return true;
            }
        }

        // Else return false
        return false;
    }

    /**
     * Get owning critique
     */
    public function critique()
    {
        return $this->belongsTo(Critique::class);
    }

    /**
     * Topics that belongs to opinion
     */
    public function topics()
    {
        return $this->belongsToMany(Topic::class)->withTimestamps();
    }

    /**
     * Get critiques who liked opinion
     */
    public function likers()
    {
        return $this->belongsToMany(Critique::class)->withTimestamps();
    }
}
