<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite1e562ea2a29c952f7370278f26cf95c
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite1e562ea2a29c952f7370278f26cf95c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite1e562ea2a29c952f7370278f26cf95c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite1e562ea2a29c952f7370278f26cf95c::$classMap;

        }, null, ClassLoader::class);
    }
}
