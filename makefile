default: test

test:
	phpunit --strict --colors --bootstrap=bootstrap.php tests/AllTests.php
