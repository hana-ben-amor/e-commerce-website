<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    //on a besoin de l'entity manager interface

    #[Route('/category/new', name: 'app_new_category')]
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
}
