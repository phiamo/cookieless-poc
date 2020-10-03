<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\Request;

class MerchantTracker
{
    private ClickTracker $clickTracker;
    private ClientManager $clientManager;

    public function __construct(
        ClickTracker $clickTracker,
        ClientManager $clientManager
    )
    {
        $this->clickTracker = $clickTracker;
        $this->clientManager = $clientManager;
    }

    public function track(
        Request $request
    )
    {
        $clickId = $request->query->get('clickId');

        $csFingerPrint = $request->query->get('clientSideFingerPrint');
        $clientUUID = $request->query->get('clientUUID');

        $client = $this->clientManager->findOrCreate($clientUUID, $csFingerPrint);

        if($clickId) {
            $this->clickTracker->track($clickId, $client);
        }

        return $client;
    }
}
