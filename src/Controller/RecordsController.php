<?php

namespace Fmaj\LaposteDatanovaBundle\Controller;

use Fmaj\LaposteDatanovaBundle\Manager\RecordsManager;
use Fmaj\LaposteDatanovaBundle\Model\Download;
use Fmaj\LaposteDatanovaBundle\Model\Search;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RecordsController extends AbstractController
{
    /**
     * @var RecordsManager
     */
    private $recordsManager;

    public function __construct(RecordsManager $recordsManager)
    {
        $this->recordsManager = $recordsManager;
    }

    public function searchAction(string $dataset, string $query, string $sort, int $rows, int $start): Response
    {
        $search = new Search($dataset);
        $search
            ->setFilter($query)
            ->setStart($start)
            ->setSort($sort)
            ->setRows($rows);
        $results = $this->recordsManager->search($search);

        return new JsonResponse($results);
    }

    public function downloadAction(string $dataset, string $_format, string $query): Response
    {
        $response = new Response();
        $download = new Download($dataset, $_format);
        $download->setFilter($query);
        $local = $this->recordsManager->getLocalDatasetContent($download);
        $results = $local ?? $this->recordsManager->download($download);
        switch (strtolower($_format)) {
            case 'json':
                $results = json_encode($results);
                break;
            case 'csv':
                $response->headers->set('Content-Type', 'text/csv');
                break;
        }
        $response->setContent($results);
        $response->headers->set(
            'Content-Disposition',
            sprintf('attachment; filename="%s.%s"', $dataset, $_format)
        );

        return $response;
    }
}
