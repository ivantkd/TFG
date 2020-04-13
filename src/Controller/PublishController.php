<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class PublishController extends AbstractController
{
    /**
     * @Route("/publish/{topic}", name="publisher", methods={"POST"})
     */
    public function index(Publisher $publisher, $topic, Request $request)
    {
        $publisher(new Update($topic, $request->getContent()));
        return new Response('success');
    }
}
