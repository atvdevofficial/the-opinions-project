<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Critique extends Model
{
    use HasFactory;

    /**
     * Attributes to append
     */
    protected $appends = [
        'statistics',
        'is_following'
    ];

    /**
     * Mass assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'username',
    ];

    /**
     * Attributes that are hidden in arrays\
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at',

        'pivot',
    ];

    /**
     * Cast attributes to native types
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Mutators and accessors
     */

    /**
     * Critiques statistics
     */
    public function getStatisticsAttribute()
    {
        $topics = $this->followedTopics()->count() ?? 0;
        $followers = $this->followers()->count() ?? 0;
        $followings = $this->followings()->count() ?? 0;

        return [
            'topics' => $topics,
            'followers' => $followers,
            'followings' => $followings,
        ];
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
                return $authenticatedCritique->followings()->whereFollowedId($this->id)->exists();
            }
        }

        return false;
    }

    /**
     * Get owning user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get owned opinions
     */
    public function opinions()
    {
        return $this->hasMany(Opinion::class);
    }

    /**
     * Get followers (critiques)
     */
    public function followers()
    {
        return $this->belongsToMany(Critique::class, 'follow_critique', 'followed_id', 'follower_id');
    }

    /**
     * Get followed critiques
     */
    public function followings()
    {
        return $this->belongsToMany(Critique::class, 'follow_critique', 'follower_id', 'followed_id');
    }

    /**
     * Get followed topics
     */
    public function followedTopics()
    {
        return $this->belongsToMany(Topic::class, 'critique_topic', 'critique_id', 'topic_id');
    }

    /**
     * Get owned messages (sender)
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get owned messages (receiver)
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get liked opinions
     */
    public function likes()
    {
        return $this->belongsToMany(Opinion::class)->withTimestamps();
    }
}
