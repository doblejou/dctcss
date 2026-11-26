.PHONY: build check check-release contract-test config-test visual-check serve

build:
	sh scripts/build.sh

check:
	sh scripts/check.sh

check-release:
	php scripts/validate.php --assert-production
	sh scripts/check.sh

contract-test:
	sh scripts/test-contract-validator.sh

config-test:
	sh scripts/test-config.sh

visual-check:
	sh scripts/visual-check.sh public /tmp/dctcss-visual

serve:
	sh scripts/serve.sh
