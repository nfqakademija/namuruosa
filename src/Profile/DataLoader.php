<?php

namespace App\Profile;

use App\Repository\ReviewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class dataLoader
{
    private $reviews;
    private $paginator;
    private $request;
    private $entityManager;

    public function __construct(ReviewsRepository $reviews, PaginatorInterface $paginator, RequestStack $request, EntityManagerInterface $manager)
    {
        $this->reviews = $reviews;
        $this->paginator = $paginator;
        $this->request = $request;
        $this->entityManager = $manager;
    }

    public function getAllReviews($userId)
    {
        $queryBuilder = $this->reviews->getAllUserReviews($userId);
        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $this->request->getCurrentRequest()->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );

        return $pagination;
    }

    public function getCountReviews($userId)
    {
        return $this->reviews->getCountReviews($userId)[0][1];
    }

    public function getAverageRating($userId)
    {
        $avRating = 0;
        if (!$this->reviews->getAverageRating($userId)[0][1] == null) {
            $avRating = $this->reviews->getAverageRating($userId)[0][1];
        }
        return round($avRating, 1);
    }

    public function countUserServices($userId)
    {
      return $this->entityManager->getRepository('App:Match')->countUserServices($userId)[0][1];
    }

    public function countUserJobs($userId)
    {
      return $this->entityManager->getRepository('App:Match')->countUserJobs($userId)[0][1];
    }

    public function countUserMoney($userId): array
    {
      $money = [];
      $servicesPrices = [];
      $jobsPrices = [];


      $userServices = $this->entityManager->getRepository('App:Service')->findBy([
        'userId' => $userId
      ]);

      $userJobs = $this->entityManager->getRepository('App:Job')->findBy([
        'userId' => $userId
      ]);

      foreach ($userServices as $key => $service) {
        array_push($servicesPrices, intval($service->getPricePerHour()));
      }
      foreach ($userJobs as $key => $job) {
        array_push($jobsPrices, intval($job->getBudget()));
      }

      array_push($money, (array_sum($jobsPrices) / count($jobsPrices)));
      array_push($money, (array_sum($servicesPrices) / count($servicesPrices)));

      return $money;
    }

}
