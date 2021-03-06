<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoriesController extends AbstractController
{
    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function adminGetCategories(Request $request)
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->getAllCategories();

        $form = $this->createForm(CategoryType::class)
            ->handleRequest($request);

        if ($this->categoryCreated($form)) {
            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/categories.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function adminDeleteCategory($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em
            ->getRepository(Category::class)
            ->find($id);

        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('admin_categories');
    }

    /**
     * @param $form
     * @return bool
     */
    private function categoryCreated($form)
    {

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $newCategory = $form->getData();
            $em->persist($newCategory);
            $em->flush();

            return true;
        }
    }
}
