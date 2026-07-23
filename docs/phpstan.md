# PHPStan
Run the same checks as CI with PHP 8.2:
```sh
composer install
composer phpstan
```
The configuration is `phpstan.neon.dist`. It loads the CapCore base at `phpstan/capcore-wordpress.neon`, WordPress extensions and, where this plugin integrates with Elementor, `phpstan/elementor.neon`. The canonical base is maintained in `KreativDuo/capcore-shared-ui` and is synchronized with Shared UI releases.
The project runs at PHPStan level 5. `phpstan-baseline.neon` currently records 4 pre-existing findings as an intentionally temporary transition baseline. Every entry is path- and error-specific; do not add broad ignore rules. When a file covered by the baseline is changed, fix its listed findings and regenerate the baseline so its entries decrease. New findings are not covered and fail CI.
