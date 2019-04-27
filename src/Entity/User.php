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
     * @ORM\OneToOne(targetEntity="App\Entity\UserProfile", mappedBy="userId", cascade={"persist", "remove"})
     */
    private $userProfile;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ProfileComments", mappedBy="user_id")
     */
    private $profileComments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserRatings", mappedBy="user_id")
     */
    private $userRatings;


//    ------- End Dalius ------------


    public function __construct()
    {
        parent::__construct();
        $this->profileComments = new ArrayCollection();
        $this->userRatings = new ArrayCollection();
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

    public function setUserProfile(UserProfile $userProfile): self
    {
        $this->userProfile = $userProfile;

        // set the owning side of the relation if necessary
        if ($this !== $userProfile->getUserId()) {
            $userProfile->setUserId($this);
        }

        return $this;
    }

    /**
     * @return Collection|ProfileComments[]
     */
    public function getProfileComments(): Collection
    {
        return $this->profileComments;
    }

    public function addProfileComment(ProfileComments $profileComment): self
    {
        if (!$this->profileComments->contains($profileComment)) {
            $this->profileComments[] = $profileComment;
            $profileComment->addUserId($this);
        }

        return $this;
    }

    public function removeProfileComment(ProfileComments $profileComment): self
    {
        if ($this->profileComments->contains($profileComment)) {
            $this->profileComments->removeElement($profileComment);
            $profileComment->removeUserId($this);
        }

        return $this;
    }

    /**
     * @return Collection|UserRatings[]
     */
    public function getUserRatings(): Collection
    {
        return $this->userRatings;
    }

    public function addUserRating(UserRatings $userRating): self
    {
        if (!$this->userRatings->contains($userRating)) {
            $this->userRatings[] = $userRating;
            $userRating->setUserId($this);
        }

        return $this;
    }

    public function removeUserRating(UserRatings $userRating): self
    {
        if ($this->userRatings->contains($userRating)) {
            $this->userRatings->removeElement($userRating);
            // set the owning side to null (unless already changed)
            if ($userRating->getUserId() === $this) {
                $userRating->setUserId(null);
            }
        }

        return $this;
    }
}
