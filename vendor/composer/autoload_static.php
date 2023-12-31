<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8fa24fc818c2bba4986be23d33295430
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8fa24fc818c2bba4986be23d33295430::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8fa24fc818c2bba4986be23d33295430::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit8fa24fc818c2bba4986be23d33295430::$classMap;

        }, null, ClassLoader::class);
    }
}
