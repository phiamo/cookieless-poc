<?php


namespace App\Service;


use App\Entity\Click;
use App\Entity\Client;
use App\Repository\ClickRepository;
use Doctrine\ORM\EntityManagerInterface;

class ClickTracker
{
    private EntityManagerInterface $entityManager;
    private ClickRepository $clickRepository;
    private ClientManager $clientManager;

    public function __construct(
        ClientManager $clientManager,
        EntityManagerInterface $entityManager,
        ClickRepository $clickRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->clickRepository = $clickRepository;
        $this->clientManager = $clientManager;
    }

    // atm this is used from merchant, when the click is "recieved"
    // todo: maybe do this on the affiliate side, when the click actually happens ...
    public function track(string $clickId, Client $client)
    {
        $click = $this->clickRepository->findOneBy(['clickId' => $clickId]);

        if(!$click) {
            $click = new Click($clickId, $client);

            $this->entityManager->persist($click);
            $this->entityManager->flush();
        }
        else {
            $click->incrementVisits();
            $this->entityManager->flush();
        }
    }

    public function create(?string $clientUUID, ?string $clientSideFingerPrint): Click
    {
        $client = $this->clientManager->findOrCreate($clientUUID, $clientSideFingerPrint);
        $click = new Click(null, $client);

        $this->entityManager->persist($click);
        $this->entityManager->flush();

        return $click;
    }
}
