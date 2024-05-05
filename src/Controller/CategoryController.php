<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/admin/category', name: 'app_category')]
    public function index(CategoryRepository $repo): Response
    {
        $categories = $repo->findAll();
        return $this->render('category/index.html.twig', [
            'categories'=> $categories
        ]);
    }

    //on a besoin de l'entity manager interface

    #[Route('/admin/category/new', name: 'app_new_category')]
    public function addCategory(EntityManagerInterface $entityManager,Request $request): Response
    {
        $category=new Category();
        $form = $this->createForm(CategoryFormType::class,$category);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Category created successfully!');
            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/new.html.twig',
        [
            'form'=>$form->createView()
        ]
    );
    }

    #[Route('/admin/category/{id}/update', name: 'app_update_category')]
    public function updateCategory(Category $category,EntityManagerInterface $entityManager,Request $request): Response
    {
        $form = $this->createForm(CategoryFormType::class,$category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Category updated successfully!');
            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/update.html.twig',
        [
            'form'=>$form->createView()
        ]
    );
    }

    #[Route('/admin/category/{id}/delete', name: 'app_delete_category')]
    public function deleteCategory(Category $category,EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($category);
            $entityManager->flush();
            $this->addFlash('success', 'Category udeleted successfully!');
            return $this->redirectToRoute('app_category');
        
    }
}
