<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *      description="Order model",
 *      title="Order model"
 * )
 * @OA\Property(property="id", type="number", description="ID")
 * @OA\Property(property="product_id", type="number", description="상품 ID")
 * @OA\Property(property="user_id", type="number", description="유저 ID")
 * @OA\Property(property="created_at", type="string", format="date-time", description="created_at")
 * @OA\Property(property="updated_at", type="string", format="date-time", description="updated_at")
 *
 */
class Order extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
