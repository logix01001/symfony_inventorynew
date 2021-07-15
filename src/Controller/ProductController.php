<?php

namespace App\Controller;

use App\Entity\Product;
use App\RedirectMain;
use App\Form\ProductFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{

    use RedirectMain;
   

    /**
     * @Route("/",name="product.show")
     */
    public function show(Request $request): Response
    {

        if($request->get('id')){
            $id = $request->get('id');
            $product = $this->getDoctrine()
                        ->getManager()
                        ->getRepository(Product::class)
                        ->find( $id )
                        ;
            $form = $this->createForm(
                ProductFormType::class,
                $product,
                [
                    "method" => "PUT",
                    "action" => $this->generateUrl('product.update', ["id" => $id ])
                ]
            );

        }else{

            $form = $this->createForm(
                ProductFormType::class,
                null,
                [
                    "method" => "POST",
                    "action" => $this->generateUrl('product.store')
                ]
            );

        }


        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/store",name="product.store", methods={"POST"})
     */
    public function store(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ProductFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();

            $em->persist($product);
            $em->flush();

            $this->addFlash(
                'notice',
                'New product has been successfully saved!.'
            );
        }

        return $this->redirectToIndex();
    }

    /**
     * @Route("/update/{id}",name="product.update", methods={"PUT"})
     */
    public function update(int $id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);

        $form = $this->createForm(
                    ProductFormType::class,
                    $product, 
                    [ "method" => 'PUT']
                );
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();

            $em->persist($product);
            $em->flush();

            $this->addFlash(
                'notice',
                "{$product->getName()} has been successfully updated!."
            );
        }

        return $this->redirectToIndex();
    }



    /**
     * @Route("/delete/{id}",name="product.delete", methods={"DELETE"})
     */
    public function destroy(int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);


        if($product != null){
            $name = $product->getName();

            $em->remove($product);
            $em->flush();

            $this->addFlash(
                'notice',
                "{$name} has been successfully deleted!."
            );
        }

        return $this->redirectToIndex();
    }


}
