<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb79ca96693de3b86c25b4f7f0878ce69
{
    public static $prefixLengthsPsr4 = array (
        'a' => 
        array (
            'amuz\\official\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'amuz\\official\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'amuz\\official\\Skins\\PortfolioSkin' => __DIR__ . '/../..' . '/skins/portfolio/PortfolioSkin.php',
        'amuz\\official\\Theme\\Theme' => __DIR__ . '/../..' . '/theme/Theme.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb79ca96693de3b86c25b4f7f0878ce69::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb79ca96693de3b86c25b4f7f0878ce69::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb79ca96693de3b86c25b4f7f0878ce69::$classMap;

        }, null, ClassLoader::class);
    }
}
