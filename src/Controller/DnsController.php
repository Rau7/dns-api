<?php

/*
@Author : Alp Toker
This is a Controller for do the actual work.
*/

namespace App\Controller;

use App\Service\DnsService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DnsController extends AbstractController
{

    public function __construct(private DnsService $dnsSrv) {}

    /*
        For seeing the dns form page
    */
    #[Route('/', name: 'dns_home')]
    public function index(): Response
    {
        return $this->render('dns/index.html.twig');
    }

    /*
        For getting the dns records,
        By using DnsService we can check the constraints
    */
    #[Route('/api/dns', name: 'api_dns', methods: ['GET'])]
    public function getDnsRecords(Request $request): JsonResponse
    {
        $domain = $request->query->get('domain');

        if (!$domain) {
            return $this->json([
                'error' => 'Domain name is required',
            ], Response::HTTP_BAD_REQUEST);
        }
        //Check if it is a valid domain
        if (!$this->dnsSrv->isValidDomain($domain)) {
            return $this->json([
                'error' => 'Invalid domain format',
                'domain' => $domain
            ], Response::HTTP_BAD_REQUEST);
        }

        //Check if the domain exists
        if (!$this->dnsSrv->isDomainExists($domain)) {
            return $this->json([
                'error' => 'Domain does not exist',
                'domain' => $domain
            ], Response::HTTP_NOT_FOUND);
        }

        //Getting the records but we should be aware that it may throw an exception
        try {
            $records = $this->dnsSrv->getDnsRecords($domain);
            
            return $this->json([
                'success' => true,
                'domain' => $domain,
                'records' => $records
            ]);
            
        } catch (\Exception $e) {

            return $this->json([
                'error' => 'Failed to fetch DNS records',
                'domain' => $domain
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }




}