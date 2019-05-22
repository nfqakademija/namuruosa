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
     * @Route("/adminas/category/add", name="admin_category_add")
     */
    public function addJob(Request $request)
    {
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $category = $form->getData();
//            $job->setUserId($this->getUser());
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('my_jobs');
        }

        return $this->render('admin/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/adminas", name="admin_home")
     */
    public function adminHome()
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->getAllCategories();

        return $this->render('admin/categories/getAll.html.twig', [
            'categories' => $categories
        ]);
    }

}