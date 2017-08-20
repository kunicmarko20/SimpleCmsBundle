<?php
/**
 * Created by PhpStorm.
 * User: Marko Kunic
 * Date: 8/4/17
 * Time: 15:36
 */

namespace KunicMarko\SimpleCmsBundle\Controller;

use KunicMarko\SimpleCmsBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{

    public function indexAction(Page $page)
    {
        if (!$page) {
            throw $this->createNotFoundException('The page does not exist.');
        }

        $directory = $this->getParameter('simple_cms.template_directory');

        return $this->render($directory . $page->getTemplate(), ['object' => $page]);
    }
}
