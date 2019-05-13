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


/**
 *
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Entity\Repository\JobRepository")
 * @ORM\Table(name="job")
 * @ORM\HasLifecycleCallbacks()
 */
class Job
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="jobs")
     */
    private $category;

    /**
     * @var
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;


    /**
     * @ORM\Column(type="text")
     */
    protected $description;

//    /**
//     * @ORM\Column(type="text", nullable=true)
//     */
//    protected $category1;
//
//    /**
//     * @ORM\Column(type="text", nullable=true)
//     */
//    protected $category2;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $upload;

    /**
     * @ORM\Column(type="text")
     */
    protected $payType;

    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    protected $budget;

    /**
     * @ORM\Column(type="decimal", nullable=false, precision=17, scale=14)
     */
    protected $lat;

    /**
     * @ORM\Column(type="decimal", nullable=false, precision=17, scale=14)
     */
    protected $lon;

    /**
     * @ORM\Column(type="string", length=120, nullable=false)
     */
    protected $address;

    /**
     * @var
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateEnd;

    /**
     * @var
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $activeTill;





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

//    /**
//     * @return mixed
//     */
//    public function getCategory1()
//    {
//        return $this->category1;
//    }
//
//    /**
//     * @param mixed $category1
//     */
//    public function setCategory1($category1): void
//    {
//        $this->category1 = $category1;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getCategory2()
//    {
//        return $this->category2;
//    }
//
//    /**
//     * @param mixed $category2
//     */
//    public function setCategory2($category2): void
//    {
//        $this->category2 = $category2;
//    }

    /**
     * @return mixed
     */
    public function getUpload()
    {
        return $this->upload;
    }

    /**
     * @param mixed $upload
     */
    public function setUpload($upload): void
    {
        $this->upload = $upload;
    }

    /**
     * @return mixed
     */
    public function getPayType()
    {
        return $this->payType;
    }

    /**
     * @param mixed $payType
     */
    public function setPayType($payType): void
    {
        $this->payType = $payType;
    }

    /**
     * @return mixed
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * @param mixed $budget
     */
    public function setBudget($budget): void
    {
        $this->budget = $budget;
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
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * @param mixed $dateEnd
     */
    public function setDateEnd($dateEnd): void
    {
        $this->dateEnd = $dateEnd;
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


}