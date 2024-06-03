<?php

namespace App\Models;

use App\Contracts\InteractsWithSearch;
use App\Enum\Product\Currency;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;

/**
 * @Order
 * @property string $uuid
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property-read User $customer
 * @property-read Collection<Product> $products
 */
class Order extends Model implements InteractsWithSearch
{
    use HasUuids,
        SoftDeletes,
        HasFactory,
        Searchable;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'user_id',
        'session_id',
        'status',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_uuid');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product')
            ->with(['currentPrices'])
            ->withPivot([
                'quantity',
            ]);
    }

    public function amount(Currency $currency): float
    {
        if (!$this->relationLoaded('products')) {
            $this->load('products');
        }

        return $this->products
            ->sum(function (Product $product) use ($currency): mixed {
                /** @var ProductPrice $price */
                $price = $product->currentPrices()
                    ->where('currency', $currency)
                    ->firstOrFail();

                return $price->amount * $product->pivot->quantity;
            });
    }
}
