<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit56ae6d6e625024b895a5a5b4bcb15ca9
{
    public static $prefixLengthsPsr4 = array (
        'a' => 
        array (
            'amuz\\contact\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'amuz\\contact\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit56ae6d6e625024b895a5a5b4bcb15ca9::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit56ae6d6e625024b895a5a5b4bcb15ca9::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
