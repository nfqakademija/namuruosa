<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.5.11
 * Time: 11.56
 */

namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController {

    /**
     * @Route("/admin/category", name="admin_category")
     */
    public function adminHome(Request $request)
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->getAllCategories();

        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $category = $form->getData();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('my_jobs');
        }

        return $this->render('admin/categories.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

}