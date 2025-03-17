<?xml version="1.0" encoding="UTF-8"?>
<phpunit
    backupGlobals="false"
    backupStaticAttributes="false"
    bootstrap="vendor/autoload.php"
    colors="true"
    verbose="true"
>
    <testsuites>
        <testsuite name="Feature Tests">
            <directory>./tests/Feature</directory>
        </testsuite>
        <testsuite name="Unit Tests">
            <directory>./tests/Unit</directory>
        </testsuite>
    </testsuites>

    <listeners>
        <listener class="PHPUnit\Util\PHP\DeprecatedConfigurationException" file="vendor/phpunit/phpunit/src/Util/PHP/DeprecatedConfigurationException.php" />
    </listeners>
</phpunit>
