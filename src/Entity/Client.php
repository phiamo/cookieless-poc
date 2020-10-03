<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="guid")
     */
    private string $guid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $ssFingerprint;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $csFingerprint;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private \DateTime $updated;

    /**
     * @ORM\OneToMany(targetEntity=Click::class, mappedBy="client", orphanRemoval=true)
     */
    private Collection $clicks;

    public function __construct($guid, $ssFingerprint, $csFingerprint)
    {
        $this->guid = $guid;
        $this->ssFingerprint = $ssFingerprint;
        $this->csFingerprint = $csFingerprint;
        $this->clicks = new ArrayCollection();
        $this->updated = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setGuid(string $guid): self
    {
        $this->guid = $guid;

        return $this;
    }
    public function getCsFingerprint(): ?string
    {
        return $this->csFingerprint;
    }
    public function setCsFingerprint(?string $csFingerprint): void
    {
        $this->csFingerprint = $csFingerprint;
    }
    public function getGuid(): string
    {
        return $this->guid;
    }

    public function getSsFingerprint(): string
    {
        return $this->ssFingerprint;
    }
}
