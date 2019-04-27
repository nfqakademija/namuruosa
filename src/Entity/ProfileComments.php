<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfileCommentsRepository")
 */
class ProfileComments
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="profileComments")
     */
    private $user_id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="profileComments")
     */
    private $commentator_id;

    /**
     * @ORM\Column(type="text")
     */
    private $comment_text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function __construct()
    {
        $this->user_id = new ArrayCollection();
        $this->commentator_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserId(): Collection
    {
        return $this->user_id;
    }

    public function addUserId(User $userId): self
    {
        if (!$this->user_id->contains($userId)) {
            $this->user_id[] = $userId;
        }

        return $this;
    }

    public function removeUserId(User $userId): self
    {
        if ($this->user_id->contains($userId)) {
            $this->user_id->removeElement($userId);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getCommentatorId(): Collection
    {
        return $this->commentator_id;
    }

    public function addCommentatorId(User $commentatorId): self
    {
        if (!$this->commentator_id->contains($commentatorId)) {
            $this->commentator_id[] = $commentatorId;
        }

        return $this;
    }

    public function removeCommentatorId(User $commentatorId): self
    {
        if ($this->commentator_id->contains($commentatorId)) {
            $this->commentator_id->removeElement($commentatorId);
        }

        return $this;
    }

    public function getCommentText(): ?string
    {
        return $this->comment_text;
    }

    public function setCommentText(string $comment_text): self
    {
        $this->comment_text = $comment_text;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
