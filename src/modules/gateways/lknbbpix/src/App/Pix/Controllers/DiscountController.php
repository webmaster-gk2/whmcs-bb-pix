<?php

namespace Lkn\BBPix\App\Pix\Controllers;

use Lkn\BBPix\App\Pix\Repositories\DiscountRepository;
use Lkn\BBPix\Helpers\Response;
use Lkn\BBPix\Helpers\View;
use WHMCS\Database\Capsule;

final class DiscountController
{
    private DiscountRepository $discountRepository;

    public function __construct()
    {
        $this->discountRepository = new DiscountRepository();
    }

    public function index(): string
    {
        $this->discountRepository->paginateIndex();
        $productsLabelsByIdList = Capsule::table('tblproducts')->get(['id', 'name'])->pluck('name', 'id')->toArray();
        $productsDiscounts = $this->discountRepository->paginateIndex();

        return View::render(
            'discount_per_product.index',
            [
                'products_labels_by_id_list' => $productsLabelsByIdList,
                'products_discounts' => $productsDiscounts['data']['discounts']
            ]
        );
    }

    public function createOrUpdate(int $productId, float $percentage): void
    {
        $response = $this->discountRepository->createOrUpdate($productId, $percentage);

        Response::api($response['success'], ['msg' => $response['data']['error'] ?? '']);
    }

    public function delete(int $productId): void
    {
        $response = $this->discountRepository->delete($productId);

        Response::api($response['success'], ['msg' => $response['data']['error'] ?? '']);
    }
}
