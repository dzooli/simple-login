<?php

namespace App\Controllers;

use Framework\Web\Response;
use Framework\Web\Controller;

class SiteController extends Controller
{
    public function actionIndex(): Response
    {
        return new Response('hello');
    }
}
