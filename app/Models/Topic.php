<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

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
     * Opinions that belongs to opinion
     */
    public function opinions() {
        return $this->belongsToMany(Opinion::class);
    }

    /**
     * Get crituques that follow topic
     */
    public function followedTopics() {
        return $this->belongsToMany(Critique::class, 'critique_topic', 'topic_id', 'critique_id');
    }
}
