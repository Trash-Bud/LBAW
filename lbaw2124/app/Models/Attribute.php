<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = array('attribute_type', 'id_product');
    protected $table = 'attributes';
    protected $connection = 'pgsql';

    protected $fillable = [
        'id_product', 'value', 'attribute_type'
    ];

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('products', 'id', 'id_product');
    }
}
