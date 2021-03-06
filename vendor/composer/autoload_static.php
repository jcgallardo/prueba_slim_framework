<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4634f4fd398ef1d67498edc1ce5ea88e
{
    public static $prefixesPsr0 = array (
        'S' => 
        array (
            'Slim' => 
            array (
                0 => __DIR__ . '/..' . '/slim/slim',
            ),
        ),
    );

    public static $classMap = array (
        'PiramideUploader' => __DIR__ . '/../..' . '/piramide-uploader/PiramideUploader.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit4634f4fd398ef1d67498edc1ce5ea88e::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit4634f4fd398ef1d67498edc1ce5ea88e::$classMap;

        }, null, ClassLoader::class);
    }
}
