<?php

namespace App\Controller;

use App\Service\MerchantTracker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MerchantController extends AbstractController
{
    /**
     * Example merchant page
     *
     * @Route("/", host="merchant.local")
     * @Template()
     * @param MerchantTracker $merchantTracker
     * @param Request $request
     * @return string[]
     */
    public function landingPage(
        MerchantTracker $merchantTracker,
        Request $request
    ) {
        $client = $merchantTracker->track($request);

        return [
            'client' => $client
        ];
    }
}
