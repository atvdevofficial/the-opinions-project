<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Topic extends Model
{
    use HasFactory;

    /**
     * Mutators and accessors to append
     */
    protected $appends = [
        'opinion_count',
        'is_following'
    ];

    /**
     * Attributes that are mass assignable
     */
    protected $fillable = [
        'text',
    ];

    /**
     * Attributes that are cast to native types
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Attributes that are hidden in arrays\
     *
     * @var array
     */
    protected $hidden = [
        'pivot',
    ];

    /**
     * Model accessors and mutators
     */

    /**
     * Get opinion count of topic
     */
    public function getOpinionCountAttribute() {
        return $this->opinions()->count();
    }

    /**
     * If current user (critique) is following
     * this critique model
     */
    public function getIsFollowingAttribute()
    {
        if (Auth::check()) {
            $authenticatedUser = Auth::user();
            $authenticatedUserRole = $authenticatedUser->role;

            if ($authenticatedUserRole == 'CRITIQUE') {
                $authenticatedCritique = $authenticatedUser->critique;
                return $authenticatedCritique->followedTopics()->whereTopicId($this->id)->exists();
            }
        }

        return false;
    }

    /**
     * Opinions that belongs to opinion
     */
    public function opinions() {
        return $this->belongsToMany(Opinion::class)->withTimestamps();;
    }

    /**
     * Get crituques that follow topic
     */
    public function followers() {
        return $this->belongsToMany(Critique::class, 'critique_topic', 'topic_id', 'critique_id');
    }
}
