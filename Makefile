.PHONY: build check check-release contract-test config-test visual-check serve github-release refresh-benchmarks

refresh-benchmarks:
	sh scripts/refresh-benchmarks.sh

build:
	sh scripts/build.sh

check:
	sh scripts/check.sh

check-release:
	php scripts/validate.php --assert-production
	gitleaks detect --redact --no-banner --no-git --source public
	gitleaks detect --redact --no-banner --no-git --source dist
	sh scripts/check.sh

github-release:
	sh scripts/github-release.sh

contract-test:
	sh scripts/test-contract-validator.sh

config-test:
	sh scripts/test-config.sh

visual-check:
	sh scripts/visual-check.sh public /tmp/dctcss-visual

serve:
	sh scripts/serve.sh
