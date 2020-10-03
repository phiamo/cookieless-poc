<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AffiliateController extends AbstractController
{
    /**
     * Example affiliate page
     *
     * @Route("/", host="affiliate.local")
     * @Template()
     * @param string $productId
     * @param string $affiliateId
     * @return array
     */
    public function index(
        string $productId,
        string $affiliateId
    )
    {
        return [
            'affiliateId' => $affiliateId,
            'productId' => $productId,
        ];
    }
}
