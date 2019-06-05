<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.21
 * Time: 09.22
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UserServices
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Entity\Repository\ServiceRepository")
 * @ORM\Table(name="service")
 * @ORM\HasLifecycleCallbacks()
 */
class Service
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $userId;

    /**
     * @var
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Type(type="array")
     */
    private $serviceType;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     *
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     */
    protected $description;

    /**
     * @var
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     * @Assert\Type(type="datetime")
     */
    protected $activeTill;

    /**
     * @var
     * @ORM\Column(type="decimal", nullable=false, precision=17, scale=14)
     */
    protected $lat;

    /**
     * @var
     * @ORM\Column(type="decimal", nullable=false, precision=17, scale=14)
     */
    protected $lon;

    /**
     * @ORM\Column(type="string", length=120, nullable=false)
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     */
    protected $address;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    protected $distance;

    /**
     * @var
     * @ORM\Column(type="decimal")
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     */
    protected $maxDistance;

    /**
     * @var
     * @ORM\Column(type="decimal")
     * @Assert\NotBlank
     * @Assert\Type(type="numeric")
     */
    protected $pricePerHour;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="services")
     */
    private $category;

    protected $userRating;



    //    ----------- Methods ----------

    public function __construct()
    {
        $this->category = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @throws \Exception
     *
     * @ORM\PrePersist()
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @throws \Exception
     *
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getServiceType()
    {
        return $this->serviceType;
    }

    /**
     * @param mixed $serviceType
     */
    public function setServiceType($serviceType): void
    {
        $this->serviceType = $serviceType;
    }


    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getActiveTill()
    {
        return $this->activeTill;
    }

    /**
     * @param mixed $activeTill
     */
    public function setActiveTill($activeTill): void
    {
        $this->activeTill = $activeTill;
    }

    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param mixed $lat
     */
    public function setLat($lat): void
    {
        $this->lat = $lat;
    }

    /**
     * @return mixed
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * @param mixed $lon
     */
    public function setLon($lon): void
    {
        $this->lon = $lon;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address=$address;
    }

    /**
     * @return mixed
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param mixed $distance
     */
    public function setDistance($distance): void
    {
        $this->distance = $distance;
    }

    /**
     * @return mixed
     */
    public function getMaxDistance()
    {
        return $this->maxDistance;
    }

    /**
     * @param mixed $maxDistance
     */
    public function setMaxDistance($maxDistance): void
    {
        $this->maxDistance = $maxDistance;
    }

    /**
     * @return mixed
     */
    public function getPricePerHour()
    {
        return $this->pricePerHour;
    }

    /**
     * @param mixed $pricePerHour
     */
    public function setPricePerHour($pricePerHour): void
    {
        $this->pricePerHour = $pricePerHour;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }
        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->category->contains($category)) {
            $this->category->removeElement($category);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserRating()
    {
        return $this->userRating;
    }

    /**
     * @param mixed $userRating
     */
    public function setUserRating($userRating): void
    {
        $this->userRating = $userRating;
    }
}
