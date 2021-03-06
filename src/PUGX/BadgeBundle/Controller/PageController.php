<?php

/*
 * This file is part of the badge-poser package.
 *
 * (c) PUGX <http://pugx.github.io/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PUGX\BadgeBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class PageController
 *
 * @author Giorgio Cefaro <giorgio.cefaro@gmail.com>
 */
class PageController extends ContainerAware
{
    /**
     * @Route("/",
     * name = "pugx_page_home",
     * defaults = {"repository" = "phpunit/phpunit"}
     * )
     *
     * @Route("/show/{repository}",
     * name = "pugx_page_home_show",
     * requirements = {"repository" = "[A-Za-z0-9_.-]+/[A-Za-z0-9_.-]+?"}
     * )
     *
     * @Method({"GET"})
     * @Template
     * @Cache(maxage="3600", smaxage="3600", public=true)
     *
     * @return Response
     */
    public function homeAction($repository)
    {
        $redisReader = $this->container->get('stats_reader');
        $poser = $this->container->get('poser');
        $prefix = sprintf('More than %s', number_format($redisReader->totalAccess()));
        $text = 'badges served !!';
        $formats = array_diff($poser->validFormats(), ["svg"]);
 
        return array(
            'repository' => $repository,
            'badges_served_svg' => $poser->generate($prefix, $text, 'CC0066', 'flat'),
            'formats' => $formats
            );
    }

    /**
     * @Route("/show",
     *     name  = "pugx_page_show_qs"
     * )
     * @Method({"GET"})
     * @Template("PUGXBadgeBundle:Page:home.html.twig")
     * @Cache(maxage="3600", smaxage="3600", public=true)
     *
     * @return Response
     */
    public function showAction(Request $request)
    {
        $repository = $request->get('repository');

        return array('repository' => $repository);
    }
}
