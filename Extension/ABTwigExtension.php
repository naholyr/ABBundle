<?php

namespace AB\ABBundle\Extension;

use AB\ABBundle\Service\Service;

class ABTwigExtension extends \Twig_Extension
{

    protected $ab_service;

    public function __construct(Service $ab_service)
    {
        $this->ab_service = $ab_service;
    }

    public function getName()
    {
        return 'ab';
    }

    public function getFilters()
    {
        return array(
            'ab' => new \Twig_Filter_Method($this, 'getResource'),
        );
    }

    public function getResource($resource, $uid, array $parameters = null)
    {
        $resource = $this->ab_service->getResource($resource, $uid);

        if (is_string($resource) && is_array($parameters)) {
            $resource = strtr($resource, $parameters);
        }

        return $resource;
    }

}