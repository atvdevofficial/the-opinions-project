<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Critique extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name'
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
     * Get owning user
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Get owned opinions
     */
    public function opinions() {
        return $this->hasMany(Opinion::class);
    }

    /**
     * Get followers (critiques)
     */
    public function followers() {
        return $this->belongsToMany(Critique::class, 'follow_critique', 'followed_id', 'follower_id');
    }

    /**
     * Get followed critiques
     */
    public function followings() {
        return $this->belongsToMany(Critique::class, 'follow_critique', 'follower_id', 'followed_id');
    }

    /**
     * Get followed topics
     */
    public function followedTopics() {
        return $this->belongsToMany(Topic::class, 'critique_topic', 'critique_id', 'topic_id');
    }
}
