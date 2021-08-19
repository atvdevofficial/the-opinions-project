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
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Get owning critique
     */
    public function critique() {
        return $this->belongsTo(Critique::class);
    }
}
