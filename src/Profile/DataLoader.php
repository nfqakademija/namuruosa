<?php

namespace App\Profile;

use App\Repository\ReviewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class DataLoader
{
    private $reviews;
    private $paginator;
    private $request;
    private $entityManager;

    public function __construct(ReviewsRepository $reviews, PaginatorInterface $paginator,RequestStack $request, EntityManagerInterface $manager)
    {
        $this->reviews = $reviews;
        $this->paginator = $paginator;
        $this->request = $request;
        $this->entityManager = $manager;
    }

    /**
     * @param $userId
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
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

    /**
     * @param $userId
     * @return mixed
     */
    public function getCountReviews($userId)
    {
        return $this->reviews->getCountReviews($userId)[0][1];
    }

    /**
     * @param $userId
     * @return float
     */
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

    /**
     * @param $userId
     * @return mixed
     */
    public function countUserJobs($userId)
    {
        return $this->entityManager->getRepository('App:Match')->countUserJobs($userId)[0][1];
    }

    /**
     * @param array $userServices
     * @param string $method
     * @return array
     */
    private function packPrices(array $userServices, string $method): array
    {

        $servicesPrices = [];

        foreach ($userServices as $key => $service) {
            array_push($servicesPrices, intval($service->$method()));
        }

        return $servicesPrices;
    }

    /**
     * @param $userId
     * @return array
     */
    public function countUserMoney($userId): array
    {

        $userServices = $this->entityManager->getRepository('App:Service')->findBy([
            'userId' => $userId
        ]);
        $userJobs = $this->entityManager->getRepository('App:Job')->findBy([
            'userId' => $userId
        ]);

        $servicesPrices = $this->packPrices($userServices, 'getPricePerHour');

        $jobsPrices = $this->packPrices($userJobs, 'getBudget');

        $jobsLength = count($jobsPrices);
        $servicesLength = count($servicesPrices);

        $avgServicePrice = 0;
        $avgJobPrice = 0;

        if ($jobsLength > 0) {
            $avgJobPrice = array_sum($jobsPrices) / $jobsLength;
        }
        if ($servicesLength > 0) {
            $avgServicePrice = array_sum($servicesPrices) / $servicesLength;
        }

        $money = [];
        array_push($money, round($avgJobPrice, 1));
        array_push($money, round($avgServicePrice, 1));

        return $money;
    }
}
