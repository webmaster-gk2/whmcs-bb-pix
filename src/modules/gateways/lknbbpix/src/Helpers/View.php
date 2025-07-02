<?php

namespace Lkn\BBPix\Helpers;

use Exception;
use Smarty;

final class View
{
    private const RESOURCES_PATH = __DIR__ . '/../resources';

    public static function render(string $view, array $vars = []): string
    {
        $viewPath = str_replace('.', '/', $view);
        $viewPath = self::RESOURCES_PATH . "/$viewPath.tpl";

        if (!file_exists($viewPath)) {
            throw new Exception('Smarty template not found.');
        }

        $smarty = new Smarty();
        $smarty = self::assignVars($smarty, $vars);

        return $smarty->fetch($viewPath);
    }

    private static function assignVars(Smarty $smartyInstance, array $vars): Smarty
    {
        foreach ($vars as $key => $value) {
            $smartyInstance->assign($key, $value);
        }

        return $smartyInstance;
    }
}
