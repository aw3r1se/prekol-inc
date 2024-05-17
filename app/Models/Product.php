<?php

namespace App\Models;

use App\Contracts\InteractsWithSearch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @Product
 * @property string $uuid
 *
 * @property string $name
 * @property string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property-read Collection<ProductPrice> $currentPrices
 * @property-read Collection<ProductPrice> $prices
 * @property-read Collection<ProductTag> $tags
 * @property-read Collection<Media> $media
 * @property-read Media|null $image
 * @property-read object $pivot
 */
class Product extends Model implements InteractsWithSearch, HasMedia
{
    use HasUuids,
        SoftDeletes,
        HasFactory,
        Searchable,
        InteractsWithMedia;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $with = [
        'currentPrices',
    ];

    public function image(): MorphOne
    {
        return $this->morphOne(
            $this->getMediaModel(),
            'model',
        )->latestOfMany();
    }

    public function media(): MorphMany
    {
        return $this->morphMany(
            $this->getMediaModel(),
            'model',
        );
    }

    /**
     * Current (latest) prices of product
     *
     * @return HasMany
     */
    public function currentPrices(): HasMany
    {
        return $this->prices()
            ->whereRaw('id IN (
                SELECT MAX(id)
                FROM product_prices
                GROUP BY product_uuid, currency
            )');
    }
    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ProductTag::class, 'product_tag_product');
    }

    public function toSearchableArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description,
            'prices' => $this->currentPrices
                ->reduce(function (string $acc, ProductPrice $price) {
                    return $acc . $price->amount . $price->currency . ' ';
                }, ''),
        ];
    }
}
