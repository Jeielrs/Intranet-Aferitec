<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0cbdc4fc2fc4d6d57cc3c94f5123009a
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'I' => 
        array (
            'INTRA\\' => 6,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'INTRA\\' => 
        array (
            0 => __DIR__ . '/..' . '/INTRA',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0cbdc4fc2fc4d6d57cc3c94f5123009a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0cbdc4fc2fc4d6d57cc3c94f5123009a::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
