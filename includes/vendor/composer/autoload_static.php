<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit50db440c84a1865160f764c18f33ffb5
{
    public static $files = array (
        '04c6c5c2f7095ccf6c481d3e53e1776f' => __DIR__ . '/..' . '/mustangostang/spyc/Spyc.php',
    );

    public static $prefixLengthsPsr4 = array (
        'e' => 
        array (
            'enshrined\\svgSanitize\\' => 22,
        ),
        'I' => 
        array (
            'Ifsnop\\' => 7,
        ),
        'D' => 
        array (
            'Dflydev\\DotAccessData\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'enshrined\\svgSanitize\\' => 
        array (
            0 => __DIR__ . '/..' . '/enshrined/svg-sanitize/src',
        ),
        'Ifsnop\\' => 
        array (
            0 => __DIR__ . '/..' . '/ifsnop/mysqldump-php/src/Ifsnop',
        ),
        'Dflydev\\DotAccessData\\' => 
        array (
            0 => __DIR__ . '/..' . '/dflydev/dot-access-data/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Dflydev\\DotAccessData\\Data' => __DIR__ . '/..' . '/dflydev/dot-access-data/src/Data.php',
        'Dflydev\\DotAccessData\\DataInterface' => __DIR__ . '/..' . '/dflydev/dot-access-data/src/DataInterface.php',
        'Dflydev\\DotAccessData\\Exception\\DataException' => __DIR__ . '/..' . '/dflydev/dot-access-data/src/Exception/DataException.php',
        'Dflydev\\DotAccessData\\Exception\\InvalidPathException' => __DIR__ . '/..' . '/dflydev/dot-access-data/src/Exception/InvalidPathException.php',
        'Dflydev\\DotAccessData\\Exception\\MissingPathException' => __DIR__ . '/..' . '/dflydev/dot-access-data/src/Exception/MissingPathException.php',
        'Dflydev\\DotAccessData\\Util' => __DIR__ . '/..' . '/dflydev/dot-access-data/src/Util.php',
        'Ifsnop\\Mysqldump\\Mysqldump' => __DIR__ . '/..' . '/ifsnop/mysqldump-php/src/Ifsnop/Mysqldump/Mysqldump.php',
        'enshrined\\svgSanitize\\ElementReference\\Resolver' => __DIR__ . '/..' . '/enshrined/svg-sanitize/src/ElementReference/Resolver.php',
        'enshrined\\svgSanitize\\ElementReference\\Subject' => __DIR__ . '/..' . '/enshrined/svg-sanitize/src/ElementReference/Subject.php',
        'enshrined\\svgSanitize\\ElementReference\\Usage' => __DIR__ . '/..' . '/enshrined/svg-sanitize/src/ElementReference/Usage.php',
        'enshrined\\svgSanitize\\Exceptions\\NestingException' => __DIR__ . '/..' . '/enshrined/svg-sanitize/src/Exceptions/NestingException.php',
        'enshrined\\svgSanitize\\Helper' => __DIR__ . '/..' . '/enshrined/svg-sanitize/src/Helper.php',
        'enshrined\\svgSanitize\\Sanitizer' => __DIR__ . '/..' . '/enshrined/svg-sanitize/src/Sanitizer.php',
        'enshrined\\svgSanitize\\data\\AllowedAttributes' => __DIR__ . '/..' . '/enshrined/svg-sanitize/src/data/AllowedAttributes.php',
        'enshrined\\svgSanitize\\data\\AllowedTags' => __DIR__ . '/..' . '/enshrined/svg-sanitize/src/data/AllowedTags.php',
        'enshrined\\svgSanitize\\data\\AttributeInterface' => __DIR__ . '/..' . '/enshrined/svg-sanitize/src/data/AttributeInterface.php',
        'enshrined\\svgSanitize\\data\\TagInterface' => __DIR__ . '/..' . '/enshrined/svg-sanitize/src/data/TagInterface.php',
        'enshrined\\svgSanitize\\data\\XPath' => __DIR__ . '/..' . '/enshrined/svg-sanitize/src/data/XPath.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit50db440c84a1865160f764c18f33ffb5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit50db440c84a1865160f764c18f33ffb5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit50db440c84a1865160f764c18f33ffb5::$classMap;

        }, null, ClassLoader::class);
    }
}
