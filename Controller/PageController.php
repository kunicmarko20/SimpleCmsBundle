<?php
/**
 * Created by PhpStorm.
 * User: Marko Kunic
 * Date: 8/4/17
 * Time: 15:36
 */

namespace KunicMarko\SimpleCmsBundle\Controller;

use Doctrine\ORM\EntityRepository;
use KunicMarko\SimpleCmsBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{

    public function indexAction(Request $request, $pageId)
    {
        /** @var EntityRepository $pageRepository */
        $pageRepository = $this->getDoctrine()->getManager()->getRepository(Page::class);

        $page = $pageRepository->findOneBy(['id' => $pageId]);

        if (!$page) {
            throw $this->createNotFoundException('The page does not exist.');
        }

        return $this->render($page->getTemplate(), ['object' => $page]);
    }
}
