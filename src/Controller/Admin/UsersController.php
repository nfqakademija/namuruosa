<?php

namespace App\Controller\Admin;

use App\Admin\RolesUnserializer;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends AbstractController
{
    /**
     * @return Response
     */
    public function getAllUsers()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->getAllUsers();

        $changedUser = new RolesUnserializer($users);
        $changedUser = $changedUser->get();

        return $this->render('admin/users.html.twig', [
            'users' => $changedUser,
        ]);
    }

    public function adminDeleteUser($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em
            ->getRepository(User::class)
            ->find($id);

        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('admin_users');
    }
}
