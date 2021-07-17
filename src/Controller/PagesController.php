<?php

namespace App\Controller;


use App\Entity\Product;
use App\Entity\Category;
use App\Form\SearchMainFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PagesController extends AbstractController
{

   
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        $products = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->findAll();

        $categories = $this->getDoctrine()
                    ->getRepository(Category::class)
                    ->findAll();

        $form = $this->createForm(SearchMainFormType::class);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $products = $this->getDoctrine()
                        ->getRepository(Product::class)
                        ->searchProductCategory( $form->get('search')->getData() )    
            ;
        }
        return $this->render('pages/index.html.twig', [
            'controller_name' => 'PagesController',
            'products' => $products,
            'categories' => $categories,
            'form' => $form->createView()
        ]);
    }
}
