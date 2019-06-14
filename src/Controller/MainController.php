<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdvertRepository;
use App\Controller\Advert;

class MainController extends AbstractController
{
   /**
   * @Route("main/{page}", name="home", requirements={"page" = "\d+"}, defaults={"page" = 1})
   * @param PostRepository $postrepository
   * @return Response
   */
    public function index(AdvertRepository $advertrepository, $page)
    {
      $adverts = $advertrepository->findAll();

      if ($page < 1) {
        throw $this->createNotFoundException('The page "'.$page.'" doesn\'t exist.');
      }
    
      return $this->render('main/index.html.twig', [
        'adverts' => $adverts
      ]);
    }

    public function addAntiSpam(Request $request)
  {
    $antispam = $this->container->get('antispam');

    $text = '...';

    if ($antispam->isSpam($text)) {
      throw new \Exception('Your message is been detected as spam content !');
    }
    
  }
}
