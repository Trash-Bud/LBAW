<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Review extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $primaryKey = 'id';
    protected $table = 'review';

    protected $fillable = [
        'id', 'rating', 'description', 'date', 'reported', 'id_user', 'id_product',
    ];

    protected $hidden = [];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('product', 'id', 'id_product');
    }


}
