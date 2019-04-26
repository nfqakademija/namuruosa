<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.21
 * Time: 09.22
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


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
     * @ORM\Column(type="string", length=255, columnDefinition="AFTER `created_at`")
     */
    private $title;


    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @var
     * @ORM\Column(type="time")
     */
    protected $activeTimeStart;

    /**
     * @var
     * @ORM\Column(type="time")
     */
    protected $activeTimeEnd;

    /**
     * @var
     * @ORM\Column(type="boolean")
     */
    protected $transport;

    /**
     * @var
     * @ORM\Column(type="boolean")
     */
    protected $education;

    /**
     * @var
     * @ORM\Column(type="boolean")
     */
    protected $cleaning;

    /**
     * @var
     * @ORM\Column(type="decimal")
     */
    protected $coordinateX;

    /**
     * @var
     * @ORM\Column(type="decimal")
     */
    protected $coordinateY;



//    ----------- Methods ----------


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
    public function getActiveTimeStart()
    {
        return $this->activeTimeStart;
    }

    /**
     * @param mixed $activeTimeStart
     */
    public function setActiveTimeStart($activeTimeStart): void
    {
        $this->activeTimeStart = $activeTimeStart;
    }

    /**
     * @return mixed
     */
    public function getActiveTimeEnd()
    {
        return $this->activeTimeEnd;
    }

    /**
     * @param mixed $activeTimeEnd
     */
    public function setActiveTimeEnd($activeTimeEnd): void
    {
        $this->activeTimeEnd = $activeTimeEnd;
    }

    /**
     * @return mixed
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * @param mixed $transport
     */
    public function setTransport($transport): void
    {
        $this->transport = $transport;
    }

    /**
     * @return mixed
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * @param mixed $education
     */
    public function setEducation($education): void
    {
        $this->education = $education;
    }

    /**
     * @return mixed
     */
    public function getCleaning()
    {
        return $this->cleaning;
    }

    /**
     * @param mixed $cleaning
     */
    public function setCleaning($cleaning): void
    {
        $this->cleaning = $cleaning;
    }

    /**
     * @return mixed
     */
    public function getCoordinateX()
    {
        return $this->coordinateX;
    }

    /**
     * @param mixed $coordinateX
     */
    public function setCoordinateX($coordinateX): void
    {
        $this->coordinateX = $coordinateX;
    }

    /**
     * @return mixed
     */
    public function getCoordinateY()
    {
        return $this->coordinateY;
    }

    /**
     * @param mixed $coordinateY
     */
    public function setCoordinateY($coordinateY): void
    {
        $this->coordinateY = $coordinateY;
    }



}