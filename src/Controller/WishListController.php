<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Service\CartService;
use App\Service\WishListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WishListController extends AbstractController
{
    #[Route('/wishlist/add/{id}', 'wishlist.add', methods: ['POST'])]
    public function add(int $id, WishListService $wishListService)
    {
        $wishListService->add($id);
        $wishList = $wishListService->getWishList();
        $totalQuantity = array_sum($wishList);
        return $this->json([
            'message' => 'Produit ajouté à la wishlist',
            'totalQuantity' => $totalQuantity,
            'wishList' => $wishList
        ]);
    }

    #[Route('/wishlist/remove/{id}', 'wishlist.remove', methods: ['POST'])]
    public function remove(int $id, WishListService $wishListService)
    {
        $wishListService->remove($id);
    }

    #[Route('/getwhishlist', methods: ['GET'])]
    public function getTotalCount(WishListService $wishListService)
    {
        $wishList = $wishListService->getWishList();
        $totalQuantity = array_sum($wishList);

        return $this->json([
            'totalQuantity' => $totalQuantity
        ]);
    }

    #[Route('/wishlist', 'wishlist.show')]
    public function show(WishListService $wishListService, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        $wishList = $wishListService->getWishListProducts();
        dd($wishList);
        return $this->render('wishlist/show.html.twig', [
            'wishList' => $wishList,
            'categories' => $categories
        ]);
    }

    #[Route('/wishlist/partial', 'wishlist.partial')]
    public function wishListPartial(WishListService $wishListService)
    {
        $wishList = $wishListService->getWishListProducts();

        return $this->render('wishlist/wishlist_partial.html.twig', [
            'wishList' => $wishList
        ]);
    }
}
