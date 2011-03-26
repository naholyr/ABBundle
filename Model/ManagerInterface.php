<?php

namespace AB\ABBundle\Model;

/**
 * AB tests manager: a "manager" will basically
 * handle the retrieving of test suites.
 *
 * @author Nicolas Chambrier <naholyr@gmail.com>
 */
interface ManagerInterface
{

    /**
     * Returns all available tests suites.
     *
     * @return TestSuiteInterface[]
     */
    public function getActiveTestSuites();

    /**
     * Retrieves a test suite by its UID.
     *
     * @param string $uid
     *
     * @return TestSuiteInterface
     */
    public function getTestSuite($uid);

    /**
     * Saves and persist a test suite.
     *
     * @param TestSuiteInterface $test
     */
    public function persist(TestSuiteInterface $test_suite);

    /**
     * Removes a persisted test suite.
     *
     * @param TestSuiteInterface $test
     */
    public function remove(TestSuiteInterface $test_suite);

    /**
     * Initializes a new test suite.
     * A new test suite is active by default.
     *
     * @param string $uid
     * @param array $versions
     *
     * @return TestSuiteInterface
     */
    public function newTestSuite($uid, array $versions = array('A', 'B'));

}
