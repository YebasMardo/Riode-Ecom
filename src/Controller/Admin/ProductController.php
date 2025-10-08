<?php
namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('admini/products', 'admin.product.')]
class ProductController extends AbstractController {

    #[Route('/', 'index')]
    public function index(ProductRepository $productRepository) {
        $products = $productRepository->findAll();
        return $this->render('admin/products/index.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/create', 'create')]
    public function create(Request $request, EntityManagerInterface $em) 
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('admin.product.index');
        }
        return $this->render('admin/products/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}', 'edit', methods: ['POST', 'GET'], requirements: ["id" => Requirement::DIGITS])]
    public function edit(Product $product, Request $request, EntityManagerInterface $em) 
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin.product.index');
        }
        return $this->render('admin/products/edit.html.twig', [
            'form' => $form,
            'product' => $product
        ]);
    }

    #[Route('/{id}', 'delete', methods: ['DELETE'], requirements: ["id" => Requirement::DIGITS])]
    public function delete(Product $product, EntityManagerInterface $em) {
        $em->remove($product);
        $em->flush();
        $this->addFlash('success', 'La recette a bien été supprimée');
        return $this->redirectToRoute('admin.product.index');
    }
}