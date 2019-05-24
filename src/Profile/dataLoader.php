<?php

namespace App\Profile;

use App\Repository\ReviewsRepository;
use Knp\Component\Pager\PaginatorInterface;

class dataLoader
{

    private $reviews;
    private $paginator;

    public function __construct(ReviewsRepository $reviews, PaginatorInterface $paginator)
    {
        $this->reviews = $reviews;
        $this->paginator = $paginator;

    }

    public function getAllReviews($userId, $request)
    {
        $queryBuilder = $this->reviews->getAllUserReviews($userId, $request);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );

        return $pagination;
    }

    public function getCountReviews($userId)
    {
        return $this->reviews->getCountReviews($userId);
    }

    public function getAverageRating($userId)
    {
        return $this->reviews->getAverageRating($userId);
    }

}
