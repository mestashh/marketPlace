<?php

namespace App\Services;

use App\Exceptions\ProductImage\ProductImagePositionExistsException;
use App\Exceptions\ProductImage\ProductImagePositionsFullException;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProductImageService
{
    /**
     * @throws ProductImagePositionExistsException
     * @throws Throwable
     */
    public function create(array $data, Product $product): ProductImage
    {
        return DB::transaction(function () use ($data, $product) {
            $images = $product->productImages()->lockForUpdate()->get();

            $usedPositions = $images->where('is_main', false)->pluck('position')->toArray();

            $freePosition = collect(range(1, 5))
                ->first(fn ($pos) => ! in_array($pos, $usedPositions));
            if ($data['is_main'] === true) {
                $currentMain = $images->firstWhere('is_main', true);
                if ($currentMain) {
                    if (! $freePosition) {
                        throw new ProductImagePositionsFullException;
                    } else {
                        $currentMain->update([
                            'is_main' => false,
                            'position' => $freePosition,
                        ]);
                    }
                }
                $data['position'] = 0;
            }

            if ($data['is_main'] === false) {
                if ($images
                    ->where('position', $data['position'])
                    ->isNotEmpty()) {
                    if (! $freePosition) {
                        throw new ProductImagePositionsFullException;
                    } else {
                        $data['position'] = $freePosition;
                    }
                }
            }

            return $product->productImages()->create($data);
        });
    }
}
