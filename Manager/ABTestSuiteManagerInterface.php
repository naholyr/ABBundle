<?php

namespace AB\ABBundle\Manager;

use AB\ABBundle\TestSuite\ABTestSuiteInterface;

/**
 * AB tests manager: a "manager" will basically
 * handle the retrieving of test suites.
 *
 * @author Nicolas Chambrier <naholyr@gmail.com>
 */
interface ABTestSuiteManagerInterface
{

    /**
     * Returns all available tests suites.
     *
     * @return array(ABTestSuiteInterface)
     */
    public function getActiveTestSuites();

}
