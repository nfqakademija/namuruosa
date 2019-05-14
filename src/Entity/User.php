<?php
// src/Entity/User.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


//    ------- Dalius -------------


    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $lastName;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserProfile", mappedBy="user_id", cascade={"persist", "remove"})
     */
    private $userProfile;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reviews", mappedBy="user_id")
     */
    private $hasReviews;


//    ------- End Dalius ------------


    public function __construct()
    {
        parent::__construct();
        $this->hasReviews = new ArrayCollection();
        // your own logic
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getUserProfile(): ?UserProfile
    {
        return $this->userProfile;
    }

    public function setUserProfile(?UserProfile $userProfile): self
    {
        $this->userProfile = $userProfile;

        // set (or unset) the owning side of the relation if necessary
        $newUser_id = $userProfile === null ? null : $this;
        if ($newUser_id !== $userProfile->getUserId()) {
            $userProfile->setUserId($newUser_id);
        }

        return $this;
    }

    /**
     * @return Collection|Reviews[]
     */
    public function getHasReviews(): Collection
    {
        return $this->hasReviews;
    }

    public function addHasReviews(Reviews $hasReviews): self
    {
        if (!$this->hasReviews->contains($hasReviews)) {
            $this->hasReviews[] = $hasReviews;
            $hasReviews->setUserId($this);
        }

        return $this;
    }

    public function removeHasReviews(Reviews $hasReviews): self
    {
        if ($this->hasReviews->contains($hasReviews)) {
            $this->hasReviews->removeElement($hasReviews);
            // set the owning side to null (unless already changed)
            if ($hasReviews->getUserId() === $this) {
                $hasReviews->setUserId(null);
            }
        }

        return $this;
    }
}
