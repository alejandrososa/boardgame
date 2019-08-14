<?php

namespace Mayordomo\Ui\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomePageController extends BaseController
{
    public function index(Request $request)
    {
//        echo '<pre>';print_r([__CLASS__,__LINE__,__METHOD__, $request->attributes]);echo '</pre>';die();
        return $this->render('homepage.html.php', [] );
//        echo '<pre>';print_r([__CLASS__,__LINE__,__METHOD__, $this->container->get('')]);echo '</pre>';die();
        return new Response('This is the first Symfony4 page!');
    }
}