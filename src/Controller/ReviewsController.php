<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ReviewType;
use App\Entity\User;
use ReCaptcha\ReCaptcha;


class ReviewsController extends AbstractController
{

    /**
     * @Route("/review/user/{userId}", name="reviewUser", requirements={"userId"="\d+"})
     */

    public function reviewProfile(Request $request, $userId)
    {
        $form = $this->createForm(ReviewType::class);
        $form->handleRequest($request);

        $estimator = $this->getUser();
        $ratedUser = $this->getDoctrine()->getRepository(User::class)->find($userId);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $review = $form->getData();

            $review->setUserId($ratedUser);
            $review->setEstimatorId($estimator);
            $review->setCreatedAt(new \DateTime());

            $entityManager->persist($review);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Jūsų vertinimas išsaugotas!'
            );

            return $this->redirectToRoute('otherUserProfile', ['userId' => $userId]);
        }

        return $this->render('reviews/rateUser.html.twig', [
            'form' => $form->createView(),
            'userId' => $userId
        ]);
    }
}
