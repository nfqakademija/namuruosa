<?php

namespace App\Profile;

use App\Repository\ReviewsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class dataLoader
{

    private $reviews;
    private $paginator;

    public function __construct(ReviewsRepository $reviews, PaginatorInterface $paginator)
    {
        $this->reviews = $reviews;
        $this->paginator = $paginator;
    }

    public function getAllReviews($id, $request)
    {
        $queryBuilder = $this->reviews->getAllUserReviews($id);

        $pagination = $this->paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );
//      dump($pagination);

        return $pagination;
    }

    public function getCountReviews($id)
    {
        return $this->reviews->getCountReviews($id);
    }

    public function getAverageRating($id)
    {
        return $this->reviews->getAverageRating($id);
    }

}
