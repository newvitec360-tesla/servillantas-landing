<?php
declare(strict_types=1);

namespace App\Core;

final class View
{
    public static function render(string $view, array $data = [], ?string $layout = null): void
    {
        $viewPath = APP_PATH . '/Views/' . trim($view, '/') . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(500);
            echo "Vista no encontrada: " . htmlspecialchars($viewPath, ENT_QUOTES, 'UTF-8');
            return;
        }

        extract($data, EXTR_SKIP);
        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        if ($layout) {
            $layoutPath = APP_PATH . '/Views/' . trim($layout, '/') . '.php';
            if (!file_exists($layoutPath)) {
                http_response_code(500);
                echo "Layout no encontrado: " . htmlspecialchars($layoutPath, ENT_QUOTES, 'UTF-8');
                return;
            }
            require $layoutPath;
            return;
        }

        echo $content;
    }
}
