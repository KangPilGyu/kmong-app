<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *      description="Product model",
 *      title="Product model"
 * )
 * @OA\Property(property="id", type="number", description="ID")
 * @OA\Property(property="product_name", type="string", description="상품명")
 * @OA\Property(property="product_price", type="number", description="상품가격 (단위: won)")
 * @OA\Property(property="created_at", type="string", format="date-time", description="created_at")
 * @OA\Property(property="updated_at", type="string", format="date-time", description="updated_at")
 *
 */
class Product extends Model
{
    protected $fillable = [
        'product_name',
        'product_price',
        'creatable_id',
        'creatable_type'
    ];

    public function user()
    {
        return $this->morphTo('creatable');
    }
}
