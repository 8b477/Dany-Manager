<?php

declare(strict_types=1);

namespace App\Configs;

final class AppConfig
{
    private static array $config = [];

    /**
     * Charge le fichier appsettings.json (une seule fois).
     */
    private static function load(): void
    {
        if (empty(self::$config)) {
            $file = __DIR__.'/appsettings.json';
            self::$config = json_decode(file_get_contents($file), true);
        }
    }

    /**
     * Retourne une section de la configuration (ex: 'database', 'template').
     */
    public static function get(string $section): array
    {
        self::load();
        return self::$config[$section];
    }

    /**
     * Retourne la config database.
     */
    public static function database(): array
    {
        return self::get('database');
    }

    /**
     * Retourne la config Template avec les chemins resolus en absolu.
     */
    public static function template(): array
    {
        return self::resolvePaths(self::get('template'));
    }

    /**
     * Resolve automatiquement les chemins dans un tableau de config.
     * Convention : toute cle qui finit par _dir ou _path est resolue.
     */
    private static function resolvePaths(array $config): array
    {
        foreach ($config as $key => &$value) {
            if (str_ends_with($key, '_dir') || str_ends_with($key, '_path')) {
                if (is_array($value)) {
                    $value = array_map(self::resolvePath(...), $value);
                } else {
                    $value = self::resolvePath($value);
                }
            }
        }
        return $config;
    }

    public static function projectRoot(): string
    {
        return dirname(__DIR__);
    }

    /**
     * Transforme un chemin relatif en chemin absolu depuis la racine du projet.
     * Si le chemin est deja absolu, il est retourne tel quel.
     */
    public static function resolvePath(string $path): string
    {
        if (str_starts_with($path, '/') || preg_match('/^[A-Za-z]:/', $path) === 1) {
            return $path;
        }
        return self::projectRoot() . '/' . $path;
    }
}
