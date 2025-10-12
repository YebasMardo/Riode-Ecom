<?php
namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use PhpParser\Node\Stmt\Foreach_;
use Symfony\Component\HttpFoundation\RequestStack;


class WishListService 
{
    private $session;

    public function __construct(RequestStack $requestStack, private ProductRepository $productRepository)
    {
        $this->session = $requestStack->getSession();
    }

    public function add(int $productId)
    {
        $wishList = $this->session->get('wishList', []);

        if (!isset($wishList[$productId])) {
            $wishList[$productId] = 0;
        }

        $wishList[$productId]++;

        $this->session->set('wishList', $wishList);
    }

    public function remove(int $productID)
    {
        $wishList = $this->session->get('wishList', []);
        unset($wishList[$productID]);
        $this->session->set('wishList', $wishList);
    }

    public function getWishList()
    {
        return $this->session->get('wishList', []);
    }

    public function getWishListProducts(): array
    {
        $wishList = $this->getWishList();
        $products = [];
        foreach ($wishList as $id => $quantity) {
            $product = $this->productRepository->find($id);
            if ($product) {
                $products[] = [
                    'id' => $product->getId(),
                    'title' => $product->getTitle(),
                    'price' => $product->getPromoPrice(), 
                    'images' => $product->getProductImages(), 
                    'quantity' => $quantity
                ];
            }
        }
        return $products;
    }

    public function clear()
    {
        $this->session->remove('wishList');
    }
}