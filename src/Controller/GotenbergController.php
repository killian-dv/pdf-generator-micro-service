<?php

namespace App\Controller;

use App\Service\GotenbergService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Pdf;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use App\Repository\PDFRepository;
use App\Entity\User;
use App\Entity\Subscription;

class GotenbergController extends AbstractController
{
    private GotenbergService $gotenbergService;

    public function __construct(GotenbergService $gotenbergService)
    {
        $this->gotenbergService = $gotenbergService;
    }

    #[Route('/gotenberg/convert', name: 'app_gotenberg_convert', methods: ['POST', 'GET'])]
    public function convert(Request $request): Response {
        $url = $request->request->get('url');

        if (!$url) {
            return new Response('URL is required.', Response::HTTP_BAD_REQUEST);
        } else {
            $pdfContent = $this->gotenbergService->generatePdfFromUrl($url);
            return new Response($pdfContent);
        }
    }
}