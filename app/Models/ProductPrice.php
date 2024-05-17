<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @ProductPrice
 * @property int $id
 * @property string $currency
 * @property float $amount
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Product $product
 */
class ProductPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'currency',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
