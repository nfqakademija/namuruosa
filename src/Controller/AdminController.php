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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController {

    /**
     * @Route("/admin/category", name="admin_category")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function adminHome(Request $request)
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->getAllCategories();

        $form = $this->createForm(CategoryType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $newCategory = $form->getData();
            $em->persist($newCategory);
            $em->flush();
            return $this->redirectToRoute('admin_category');
        }
        return $this->render('admin/categories.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param $id
     * @Route("admin/category/delete/{id}", name="admin_category_delete")
     * @return RedirectResponse
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em
            ->getRepository(Category::class)
            ->find($id);

        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('admin_category');
    }

}