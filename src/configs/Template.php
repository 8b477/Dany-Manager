<?php
declare(strict_types=1);

namespace App\Configs;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFunction;

use App\Configs\AppConfig;

class Template {
    private static ?Environment $instance = null;

    public static function get(): Environment {
        if (self::$instance === null) {
            $config = AppConfig::template();
            $loader = new FilesystemLoader($config["template_dir"]);
            self::$instance = new Environment($loader, [
                'cache' => $config['cache'],
                'debug' => true,
            ]);

            self::$instance->addFunction(new TwigFunction('session', function ($key = null) {

            if ($key === null) {
                return $_SESSION;
            }

            $parts  = explode('.', $key);   // ex: ['userSession', 'name']
            $value  = $_SESSION;

            foreach ($parts as $part) {
                if (!is_array($value) || !array_key_exists($part, $value)) {
                    return null;
                }
            $value = $value[$part];
            }

            return $value;
            }));
        }
        return self::$instance;
    }
}

