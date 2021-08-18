<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opinion extends Model
{
    use HasFactory;

    /**
     * Attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'profile_id',
        'text',
        'is_public'
    ];

    /**
     * Attributes that are hidden
     */
    protected $hidden = [
        'profile_id',
    ];

    /**
     * Attributes to cast into native types
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Get owning profile
     */
    public function profile() {
        return $this->belongsTo(Profile::class);
    }
}
