<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'text',
    ];

    /**
     * Cast attributes to native types
     */
    protected $casts = [
        'created_at' => 'datetime:Y/m/d H:i A',
        'updated_at' => 'datetime:Y/m/d H:i A',
    ];

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'updated_at',
    ];

    /**
     * Get owning sender (critique)
     */
    public function sender() {
        return $this->belongsTo(Critique::class, 'sender_id');
    }

    /**
     * Get owning receiver (critique)
     */
    public function receiver() {
        return $this->belongsTo(Critique::class, 'receiver_id');
    }
}
