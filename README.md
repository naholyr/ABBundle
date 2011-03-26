TODO

* Full documentation
* Twig helper (filter)
* Assetic integration (assets replacements)

### Usage

Standard use case: you want to test two different labels on a button, and check which one is best.

1 - Create your test suite

	$ab = $this->get('ab.testing.service');
	$m = $ab->getManager();
	$t = $m->newTestSuite('register_label'); // Default versions: A and B
	$t->addReplacements('A', array('Click here' => 'Click here to register for free')); // Version A
	$t->addReplacements('B', array('Click here' => 'Register now ! It\'s free !'));     // Version B
	$m->persist($t);

2 - In your source page, get the label depending on version randomly stored in user's session

	$ab = $this->get('ab.testing.service');
	$label = $ab->getResource('Click here', 'register_label');

3 - In your target page, give points to the current version, which has brought you a user :)

	$ab = $this->get('ab.testing.service');
	$ab->addScore(+1, 'register_label');

4 - Check the scores, and make your choice wisely !

	$ab = $this->get('ab.testing.service');
	$scores = $ab->getScores('register_label');
	$winner = $scores['A'] > $scores['B'] ? 'A' : 'B';
	$loser = $winner == 'A' ? 'B' : 'A';
	printf('%s won by %d points, against %d.', $winner, $scores[$winner], $scores[$loser]);

#### Alternative usage

Note that if you're going to call getResource(), addScore(), or getScores() more than once and
don't want to repeat the UID each time, you can start by calling:

	$ab->setCurrentTestSuite('register_label');

After this call, you can omit UID in those methods.
