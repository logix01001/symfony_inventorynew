<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\RedirectMain;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    
    use RedirectMain;
    /**
     * @Route("/", name="category.show")
     */
    public function show(Request $request): Response
    {
        if($request->get('id')){

            $id = $request->get('id');
            $em = $this->getDoctrine()->getManager();
            $category = $em->getRepository(Category::class)->find($id);
            
            $form = $this->createForm(
                CategoryFormType::class,
                $category,
                [
                    "method" => "PUT",
                    "action" => $this->generateUrl(
                            'category.update', 
                            ["id" => $id]
                        )
                ]
            ); 
        }else
        {
            $form = $this->createForm(
                CategoryFormType::class,
                null,
                [
                    "method" => "POST",
                    "action" => $this->generateUrl('category.store')
                ]);
        }

        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'form' =>  $form->createView()
        ]);
    }


    /**
     * @Route("/store", name="category.store")
     */
    public function store(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(CategoryFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist( $form->getData() );
            $em->flush();

            $this->addFlash(
                'notice',
                'New Category Added.'
            );
        }

        return $this->redirectToIndex();

    }


    /**
     * @Route("/update/{id}", name="category.update")
     */
    public function update(int $id ,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository( Category::class )->find($id);

        $form = $this->createForm(CategoryFormType::class, $category , ['method' => 'PUT']);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist( $form->getData() );
            $em->flush();

            $this->addFlash(
                'notice',
                "{$form->getData()->getName()} successfully updated"
            );
        }

        return $this->redirectToIndex();

    }


    /**
     * @Route("/delete/{id}", name="category.delete")
     */
    public function destroy(int $id ,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository( Category::class )->find($id);

        if( $category != null){
            $name = $category->getName();
         
            $em->remove( $category);
            $em->flush();

            $this->addFlash(
                'notice',
                "{$name} successfully deleted"
            );
        }

        return $this->redirectToIndex();

    }

    
}
