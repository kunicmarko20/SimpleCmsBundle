<?php
/**
 * Created by PhpStorm.
 * User: Marko Kunic
 * Date: 8/7/17
 * Time: 10:39
 */

namespace KunicMarko\SimpleCmsBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PageAdminCRUDController extends CRUDController
{
    public function clearCacheAction()
    {
        $clearCache = $this->get('simple_cms.routing_cache_handler.listener');

        $clearCache->clearRouteCache();
        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}
