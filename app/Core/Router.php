<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    /**
     * Enregistre une route
     */
    public function add(string $method, string $path, string $handler): void
    {
        $this->routes[strtoupper($method)][$path] = $handler;
    }

    /**
     * Reçoit l'URL et exécute le bon contrôleur
     */
    public function dispatch(string $uri): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        // 1. Extraire uniquement le chemin (ignore les paramètres GET ?foo=bar)
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        // 2. Nettoyage dynamique du dossier projet (ex: /SIMULITSHI-ERP/public ou /SIMULITSHI-ERP)
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? ''; // ex: /SIMULITSHI-ERP/public/index.php
        $baseFolder = dirname(dirname($scriptName));  // Récupère /SIMULITSHI-ERP
        
        // Si l'URL commence par le dossier du projet, on le retire
        if (!empty($baseFolder) && $baseFolder !== '/' && $baseFolder !== '\\' && strpos($path, $baseFolder) === 0) {
            $path = substr($path, strlen($baseFolder));
        }

        // Si l'URL contient encore /public au début, on le retire aussi
        if (strpos($path, '/public') === 0) {
            $path = substr($path, 7);
        }

        // 3. Normalisation finale du path
        $path = '/' . trim($path, '/');

        // Route par défaut si l'URL est vide ou pointe sur la racine
        if ($path === '/' || $path === '/index.php') {
            $path = '/achat';
        }

        // 4. Exécution de la route
        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            [$controllerName, $action] = explode('@', $handler);

            $controllerClass = "App\\Controllers\\" . $controllerName;

            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $action)) {
                    $controller->$action();
                    return;
                }
            }
        }

        // 404 si non trouvé
        http_response_code(404);
        echo "<div style='font-family: sans-serif; text-align: center; padding: 50px;'>";
        echo "<h1>404 - Page non trouvée</h1>";
        echo "<p>La route <strong>[" . htmlspecialchars($method) . "] " . htmlspecialchars($path) . "</strong> n'existe pas dans SIMULITSHI ERP.</p>";
        echo "</div>";
    }
}