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

        if ($form->isSubmitted() && $form->isValid()) {
            $this->saveReviewForm($request, $form, $userId);

            return $this->redirectToRoute('otherUserProfile', ['userId' => $userId]);
        }

        return $this->render('reviews/rateUser.html.twig', [
            'form' => $form->createView(),
            'userId' => $userId
        ]);
    }


    public function saveReviewForm($request, $form, $userId)
    {
        $recaptcha = new ReCaptcha('6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe');
        $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());

        if (!$resp->isSuccess()) {
            $this->addFlash(
                'notice',
                'Uždėkyte varnelę'
            );
        } else {
            $estimator = $this->getUser();
            $ratedUser = $this->getDoctrine()->getRepository(User::class)->find($userId);

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
        }
    }
}
