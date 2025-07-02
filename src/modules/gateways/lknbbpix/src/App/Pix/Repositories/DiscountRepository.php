<?php

namespace Lkn\BBPix\App\Pix\Repositories;

use Lkn\BBPix\Helpers\Response;
use Throwable;

final class DiscountRepository extends AbstractDbRepository
{
    protected string $table = 'mod_lknbbpix_discount_per_product';

    public function getPercentageForProductId(int $productId): array
    {
        try {
            $percentage = $this->query()->where('product_id', $productId)->first('percentage')->percentage;

            return Response::return(true, ['percentage' => $percentage]);
        } catch (Throwable $th) {
            return Response::return(false, ['reason' => $th->getMessage()]);
        }
    }

    public function paginateIndex(): array
    {
        try {
            $discounts = $this->query()->orderBy('id', 'desc')->get()->toArray();

            return Response::return(true, ['discounts' => $discounts]);
        } catch (Throwable $th) {
            return Response::return(false, ['reason' => $th->getMessage()]);
        }
    }

    public function createOrUpdate(int $productId, float $percentage): array
    {
        try {
            $this->query()->updateOrInsert(
                ['product_id' => $productId],
                ['percentage' => $percentage]
            );

            return Response::return(true);
        } catch (Throwable $th) {
            return Response::return(false, ['reason' => $th->getMessage()]);
        }
    }

    public function delete(int $productId): array
    {
        try {
            $this->query()->where('product_id', $productId)->delete();

            return Response::return(true);
        } catch (Throwable $th) {
            return Response::return(false, ['reason' => $th->getMessage()]);
        }
    }
}
