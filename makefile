default: test

test:
	phpunit --strict --colors --bootstrap=bootstrap.php tests/AllTests.php

mbs:
	cp lib/* ~/mbs/lib/forml
