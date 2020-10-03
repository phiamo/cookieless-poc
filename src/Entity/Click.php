<?php

namespace App\Entity;

use App\Repository\ClickRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClickRepository::class)
 */
class Click
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $clickId;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="clicks")
     * @ORM\JoinColumn(nullable=false)
     */
    private Client $client;

    /**
     * @ORM\Column(type="integer")
     */
    private int $visits = 0;

    public function __construct(?string $clickId, Client $client)
    {
        $this->clickId = $clickId ? $clickId : uniqid();
        $this->client = $client;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClickId(): ?string
    {
        return $this->clickId;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function incrementVisits()
    {
        $this->visits++;
    }
}
