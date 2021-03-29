<?php

namespace App\Controllers;

use App\Models\UserPage;

use Framework\Myy;
use Framework\Session;
use Framework\Web\Response;
use Framework\Web\Controller;

class SiteController extends Controller
{
    public function actionIndex(): Response
    {
        if (!Myy::isGuest()) {
            return new Response($this->localRedirect('site/starter'));
        }
        return new Response($this->renderView('index'));
    }

    public function actionStarter(): Response
    {
        if (!Myy::isGuest()) {
            $pages = UserPage::findPagesFor(Myy::$user_id);
            $nopages = empty($pages) || empty($pages[0]);
            if ($nopages) {
                Session::setFlash('warning', 'No pages available for you!');
            }
            return new Response($this->renderView('starter', $nopages ? ['page' => ''] : ['page' => $pages[rand(0, count($pages) - 1)]]));
        }
        Session::setFlash('danger', 'You are out of the basket. Please sign in first.');
        return new Response($this->renderView('index'));
    }
}
