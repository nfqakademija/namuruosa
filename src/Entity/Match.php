<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.27
 * Time: 12.03
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Class UserServices
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Entity\Repository\MatchRepository")
 * @ORM\Table(name="match")
 * @ORM\HasLifecycleCallbacks()
 */
class Match
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="caller_id", referencedColumnName="id")
     */
    protected $callerId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Service")
     * @ORM\JoinColumn(name="caller_service_id", referencedColumnName="id")
     */
    protected $callerServiceId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="responder_id", referencedColumnName="id")
     */
    protected $responderId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Service")
     * @ORM\JoinColumn(name="responder_service_id", referencedColumnName="id")
     */
    protected $responderServiceId;

    /**
     * @var
     *
     * @ORM\Column(type="datetime")
     */
    protected $acceptedAt;

    /**
     * @var
     *
     * @ORM\Column(type="datetime")
     */
    protected $rejectedAt;

    /**
     * @var
     *
     * @ORM\Column(type="datetime")
     */
    protected $cancelledAt;

    /**
     * @var
     *
     * @ORM\Column(type="datetime")
     */
    protected $payedAt;


//--------------- Methods -------------------
//
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
    }

    /**
     * @return mixed
     */
    public function getCallerId()
    {
        return $this->callerId;
    }

    /**
     * @param mixed $callerId
     */
    public function setCallerId($callerId): void
    {
        $this->callerId = $callerId;
    }

    /**
     * @return mixed
     */
    public function getCallerServiceId()
    {
        return $this->callerServiceId;
    }

    /**
     * @param mixed $callerServiceId
     */
    public function setCallerServiceId($callerServiceId): void
    {
        $this->callerServiceId = $callerServiceId;
    }

    /**
     * @return mixed
     */
    public function getResponderId()
    {
        return $this->responderId;
    }

    /**
     * @param mixed $responderId
     */
    public function setResponderId($responderId): void
    {
        $this->responderId = $responderId;
    }

    /**
     * @return mixed
     */
    public function getResponderServiceId()
    {
        return $this->responderServiceId;
    }

    /**
     * @param mixed $responderServiceId
     */
    public function setResponderServiceId($responderServiceId): void
    {
        $this->responderServiceId = $responderServiceId;
    }

    /**
     * @return mixed
     */
    public function getAcceptedAt()
    {
        return $this->acceptedAt;
    }

    /**
     * @param mixed $acceptedAt
     */
    public function setAcceptedAt($acceptedAt): void
    {
        $this->acceptedAt = $acceptedAt;
    }

    /**
     * @return mixed
     */
    public function getRejectedAt()
    {
        return $this->rejectedAt;
    }

    /**
     * @param mixed $rejectedAt
     */
    public function setRejectedAt($rejectedAt): void
    {
        $this->rejectedAt = $rejectedAt;
    }

    /**
     * @return mixed
     */
    public function getCancelledAt()
    {
        return $this->cancelledAt;
    }

    /**
     * @param mixed $cancelledAt
     */
    public function setCancelledAt($cancelledAt): void
    {
        $this->cancelledAt = $cancelledAt;
    }

    /**
     * @return mixed
     */
    public function getPayedAt()
    {
        return $this->payedAt;
    }

    /**
     * @param mixed $payedAt
     */
    public function setPayedAt($payedAt): void
    {
        $this->payedAt = $payedAt;
    }

}