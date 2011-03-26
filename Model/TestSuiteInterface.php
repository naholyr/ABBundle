<?php

namespace AB\ABBundle\Model;

/**
 * AB test suite: basically, an A/B test suite consists of proposing
 * different resources (template, asset, text...) depending on the
 * chosen version.
 *
 * To each version, you will attribute a score, that will help you to
 * decide which one is "the best".
 *
 * Example: you may define a test suite to check if changing background
 * color of the website has an effect on sales.
 * You keep the original CSS (name it "original") and create a new version
 * (name it "new").
 *
 * You will create a test suite like this:
 *
 * - $test_suite->getUID() // returns "color"
 * - $test_suite->getAvailableVersions() // returns array("original", "new")
 * - $test_suite->getResource("original", "style.css") // returns "style.css"
 * - $test_suite->getResource("new", "style.css") // returns "new_style.css"
 *
 * When a user buys something, you just call $test_suite->addScore("new") or
 * $test_suite->addScore("original"), depending on what version is activated
 * for this user.
 *
 * @see SessionInterface
 *
 * Then, a few days later, just check $test_suite->getScores() and see which
 * one has the best score, if the difference is significant enough to you, and
 * take your decision before disabling the test.
 *
 * @author Nicolas Chambrier <naholyr@gmail.com>
 */
interface TestSuiteInterface
{

    /**
     * @return string : the Unique ID of this test suite.
     *
     * @ensures There can't be two available test suites with same UID.
     */
    public function getUID();

    /**
     * @return string[] : available versions for this test suite.
     */
    public function getAvailableVersions();

    /**
     * @param string $version
     *
     * @param \Serializable $resource
     *
     * @return \Serializable : The corresponding resource in this test suite
     * for the current version, or null if nothing is specified for this resource
     * in this test suite. Then the original value should be used.
     *
     * @throws ErrorUnavailableVersion if the passed version does not
     * exist for this test suite.
     *
     * @see setVersion()
     */
    public function getResource($version, $resource);

    /**
     * @param string $version
     *
     * @param int $points : points to add
     *
     * @return int : new score for current version.
     *
     * @throws ErrorUnavailableVersion if the passed version does not
     * exist for this test suite.
     *
     * @see setVersion()
     */
    public function addScore($version, $points = +1);

    /**
     * @return array(string => int) : score of each version.
     */
    public function getScores();

    /**
     * Add resources replacements for this test suite.
     *
     * For each key "X", future calls to getResource("X") are expected to return
     * $replacements["X"].
     *
     * Note that a simple call to this method is not expected to persist changes,
     * you should call $manager->persist(...).
     *
     * @param string $version
     * @param array $replacements
     */
    public function addReplacements($version, array $replacements);

    /**
     * Is the test suite active (enabled) ?
     *
     * @return boolean
     */
    public function isActive();

    /**
     * Enable or disable test suite.
     *
     * Note that a simple call to this method is not expected to persist changes,
     * you should call $manager->persist(...).
     *
     * @param boolean $boolean
     */
    public function setActive($boolean);

}
