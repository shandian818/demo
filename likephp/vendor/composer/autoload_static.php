<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita024ebe4eb25401ba1d9a7e4379d8ab9
{
    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'tools\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'tools\\' => 
        array (
            0 => __DIR__ . '/..' . '/shandian818/tools/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita024ebe4eb25401ba1d9a7e4379d8ab9::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita024ebe4eb25401ba1d9a7e4379d8ab9::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
