<?php

namespace AB\ABBundle\Service;

interface ServiceInterface
{

    public function setCurrentTestSuite($uid);

    public function getResource($resource, $uid = null);

    public function addScore($points = +1, $uid = null);

    public function reloadActiveTestSuites();

}
