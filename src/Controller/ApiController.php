<?php
namespace App\Controller;

use App\Service\ClickTracker;
use App\Service\ClientManager;
use App\Service\ServerSideIdentifier;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController
{
    /**
     * Give us the server side identifier as well as a dynamically generated click id
     * we sould save it here already and just read it later on
     *
     * @Route("/api/trackingId", host="api.local", methods="GET")
     * @param Request $request
     * @param ClickTracker $clickTracker
     * @return JsonResponse
     */
    public function trackingId(
        Request $request,
        ClickTracker $clickTracker
    ) {
        $clientSideFingerPrint = $request->query->get('clientSideFingerPrint');
        $clientUUID = $request->query->get('clientUUID');

        $click = $clickTracker->create($clientUUID, $clientSideFingerPrint);

        return JsonResponse::create([
            'clickId' => $click->getClickId(),
            'serverSideFingerPrint' => $click->getClient()->getSsFingerprint()
        ]);
    }

    /**
     * Return a js snipplet, which should not change during
     * requests as long as the browser cache isnt cleared
     *
     * @Route("/api/client-id.js")
     * @param ServerSideIdentifier $serverSideIdentifier
     * @param ClientManager $clientManager
     * @param Request $request
     * @return Response
     */
    public function generateClientId(
        ClientManager $clientManager,
        Request $request
    ) {
        $csFingerprint = $request->query->get('clientSideFingerPrint');

        $client = $clientManager->findOrCreate(
            null,
            $csFingerprint
        );

        $guid = $client->getGuid();
        $clientSideFingerprint = $client->getCsFingerprint();
        $serverSideFingerprint = $client->getSsFingerprint();

        // generate the script with heredoc
        $script = <<<EOT
        var clientUUID = '${guid}';
        var clientSideFingerPrint = '${clientSideFingerprint}';
        var serverSideFingerPrint = '${serverSideFingerprint}';
        EOT;

        $response = new JsonResponse();
        // add headers for long term caching in browser (the magic itself)
        $response->headers->add([
            'Expires' => gmdate("D, d M Y H:i:s", strtotime('+13 month'))." GMT",
            'Last-Modified' => gmdate("D, d M Y H:i:s", 0)." GMT", // 1970
            'Cache-Control' => 'private',
            'Pragma' => 'private'
        ]);
        $response->setContent($script);

        return $response;
    }
}
