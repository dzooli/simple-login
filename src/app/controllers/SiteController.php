<?php

namespace App\Controllers;

use App\Models\UserPage;
use Framework\Exception\InternalErrorException;
use Framework\Exception\ViewNotFoundException;
use Framework\Myy;
use Framework\Session;
use Framework\Web\Controller;
use Framework\Web\Response;

/**
 * Controller for the '/site/<index|starter>' routes
 *
 * Called after controller and action determination by @see Application::run()
 */
class SiteController extends Controller
{
    /**
     * site/index handler
     *
     * @return Response
     */
    public function actionIndex(): Response
    {
        if (!Myy::isGuest()) {
            return new Response($this->localRedirect('site/starter'));
        }
        return new Response($this->renderView('index'));
    }

    /**
     * site/starter handler
     *
     * @return Response
     *
     * @throws InternalErrorException   when some internal error occured
     */
    public function actionStarter(): Response
    {
        if (!Myy::isGuest()) {
            $pages = UserPage::findPagesFor(Myy::$user_id);
            $nopages = empty($pages); //|| empty($pages[0]);
            if ($nopages) {
                Session::setFlash('warning', 'No pages available for you!');
            }
            try {
                $pageNo = random_int(0, count($pages) - 1);
            } catch (\Exception $ex) {
                throw new InternalErrorException('Cannot generate a valid random value');
            }
            try {
                return new Response($this->renderView('starter', $nopages ? ['page' => ''] : ['page' => $pages[$pageNo]]));
            } catch (ViewNotFoundException $ex) {
                throw new InternalErrorException('The defined view does not exists');
            }
        }

        Session::setFlash('danger', 'You are out of the basket. Please sign in first.');
        try {
            return new Response($this->renderView('index'));
        } catch (ViewNotFoundException $ex) {
            throw new InternalErrorException('The defined view does not exists');
        }
    }
}
