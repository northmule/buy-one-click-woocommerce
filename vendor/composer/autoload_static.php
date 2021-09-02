<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit515174bd7a3369551a131feca1e211ca
{
    public static $files = array (
        '732f6fe5aa64339deb73f2bf508c22bd' => __DIR__ . '/../..' . '/src/smsc-class.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
            'Coderun\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
        'Coderun\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Coderun',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit515174bd7a3369551a131feca1e211ca::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit515174bd7a3369551a131feca1e211ca::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit515174bd7a3369551a131feca1e211ca::$classMap;

        }, null, ClassLoader::class);
    }
}
