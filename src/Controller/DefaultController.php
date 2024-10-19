<?php

namespace App\Controller;

use App\Form\ApiType;
use App\Service\AuthApiService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class DefaultController extends AbstractController
{
    public function __construct(
        private readonly AuthApiService  $authApiService,
    )
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        return $this->render('index.html.twig', [
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/api', name: 'api_request')]
    public function api(Request $request, SessionInterface $session): Response
    {
        $isSubmitted = false;
        $form = $this->createForm(ApiType::class);
        $form->handleRequest($request);

        // Initialize variables
        $message = '';
        $conversation = $session->get('conversation', []);

        if ($form->isSubmitted() && $form->isValid()) {
            $isSubmitted = true;
            $message = $form->get('message')->getData();

            $result = $this->authApiService->generateText($message, $conversation);

            if (isset($result['conversation'])) {
                $conversation = $result['conversation'];
                $session->set('conversation', $conversation);
            }

            if (isset($result['error'])) {
                $response = ['error' => $result['error']];
            } else {
                $response = $result['response'];
            }
        }

        return $this->render('api.html.twig', [
            'response' => $response ?? null,
            'isSubmitted' => $isSubmitted,
            'form' => $form->createView(),
        ]);
    }

}