<?php

namespace App\Profile;

use App\Repository\ReviewsRepository;


class Manager{

  private $reviews;

  public function __construct(ReviewsRepository $reviews)
  {
    $this->reviews = $reviews;
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
