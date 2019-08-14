<?php

namespace Mayordomo\Ui\Controller;

use Symfony\Component\HttpFoundation\Request;

class ApiController extends BaseController
{
    public function index(Request $request)
    {
        return $this->render('homepage.html.php', [] );
    }
}