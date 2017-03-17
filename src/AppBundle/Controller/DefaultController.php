<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    protected function render($view, array $parameters = array(), Response $response = null)
    {
        $parameters['weather'] = $this->container->get('app.weather.api.service')->getByIp($this->container->get('request_stack')->getMasterRequest()->getClientIp());
        $parameters['base_dir'] = realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR;

        if ($this->container->has('templating')) {
            return $this->container->get('templating')->renderResponse($view, $parameters, $response);
        }

        if (!$this->container->has('twig')) {
            throw new \LogicException('You can not use the "render" method if the Templating Component or the Twig Bundle are not available.');
        }

        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($this->container->get('twig')->render($view, $parameters));

        return $response;
    }

    public function redirectToReferer(Request $request) {
        return $this->redirect(
            $request
                ->headers
                ->get('referer')
        );
    }
}
