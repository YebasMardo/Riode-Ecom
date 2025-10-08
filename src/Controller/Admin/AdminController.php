<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class AdminController extends AbstractController
{
    #[Route('/admini', name: 'admin.dashboard')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $products = $productRepository->findAll();
        return $this->render('admin/index.html.twig', [
            'categories' => $categories,
            'products' => $products
        ]);
    }
}
