<?php


namespace App\Service;


use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;

class ClientManager
{
    private EntityManagerInterface $entityManager;
    private ClientRepository $clientRepository;
    private ServerSideIdentifier $serverSideIdentifier;

    public function __construct(
        ServerSideIdentifier $serverSideIdentifier,
        EntityManagerInterface $entityManager,
        ClientRepository $clientRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->clientRepository = $clientRepository;
        $this->serverSideIdentifier = $serverSideIdentifier;
    }

    public function findOrCreate(
        ?string $guid,
        ?string $csFingerprint
    ): ?Client
    {
        $ssFingerprint = $this->serverSideIdentifier->getIdentifier();

        $client = $this->clientRepository->findClient($guid, $ssFingerprint, $csFingerprint);

        if(!$client) {
            if(!$guid) {
                $guid = self::getGuid();
            }
            $client = new Client($guid, $ssFingerprint, $csFingerprint);
            $this->entityManager->persist($client);
        } else {
            if($csFingerprint && $client->getCsFingerprint() !== $csFingerprint) {
                $client->setCsfingerprint($csFingerprint);
            }

            if($guid) {
                $client->setGuid($guid);
            }
        }
        
        $this->entityManager->flush();

        return $client;
    }

    // Get an RFC-4122 v4 compliant globaly unique identifier (used by Google Analytics)
    // see https://github.com/alekssdev/cookieless-tracking/blob/master/getclientID.php
    //
    // todo: maybe could be replaced by using ramsey uuid library
    protected static function getGuid() {
        $data = PHP_MAJOR_VERSION < 7 ? openssl_random_pseudo_bytes(16) : random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // Set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // Set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
