<?php

namespace App\Profile;

use App\Repository\ReviewsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class dataLoader
{

    private $reviews;
    private $paginator;
    private $request;

    public function __construct(ReviewsRepository $reviews, PaginatorInterface $paginator, RequestStack $request)
    {
        $this->reviews = $reviews;
        $this->paginator = $paginator;
        $this->request = $request;
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
        return $this->reviews->getAverageRating($userId);
    }

}
