<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    /**
     * Mutators and accessors to append
     */
    protected $appends = [
        'opinion_count',
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
    public function getOpinionCountAttribute() {
        return $this->opinions()->count();
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
    public function followedTopics() {
        return $this->belongsToMany(Critique::class, 'critique_topic', 'topic_id', 'critique_id');
    }
}
