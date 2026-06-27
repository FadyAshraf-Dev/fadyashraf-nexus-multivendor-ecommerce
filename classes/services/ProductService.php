<?php

declare(strict_types=1);

require_once __DIR__ . '/../repositories/ProductRepository.php';

class ProductService
{
    private ProductRepository $products;

    public function __construct()
    {
        $this->products = new ProductRepository();
    }

    /**
     * Creates a product.
     */
    public function create(array $data, int $vendorId): int
    {
        return $this->products->create(
            $data,
            $vendorId
        );
    }

    public function getById(int $id): ?array
    {
        return $this->products->getById($id);
    }

    public function getVendorProducts(int $vendorId): array
    {
        return $this->products->getAllByVendor($vendorId);
    }

    public function delete(int $id): bool
    {
        return $this->products->delete($id);
    }
}