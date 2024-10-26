<?php
    
namespace Jotform\Core;

class Route
{
    public static $patterns = [
        ':id[0-9]?' => '([0-9]+)',
        ':url[0-9]?'=> '([0-9a-zA-Z-_]+)'
    ];

    public static bool $hasRoute = false;
    //property oluşturuyoruz ve bu bir dizi olacalk
    public static array $routes = [];
    public static string $prefix = '';


    /**
     * @param $path
     * @param $callback
     * @return Route
     */

    public static function get(string $path,$callback):Route
    {
        //aşağıdaki self::preifx kısmı admin ve users dosylaraının admin/users şeklinde çalışmasını sağlıyor
        self::$routes['get'][self::$prefix . $path] = [
            'callback' => $callback
        ];
        return new self();
    }

    /**
     * @param string $path
     * @param $callback
     */

    public static function post(string $path,$callback):void
    {
        self::$routes['post'][self::$prefix . $path] = [
            'callback' => $callback
        ];
    }


    public static function dispatch()
    {
        $url = self::getUrl();
        $method = self::getMethod();
        foreach (self::$routes[$method] as $path => $props)
        {
            $callback = $props['callback'];

            foreach(self::$patterns as $key =>$pattern)
            {
                $path = preg_replace('#' . $key . '#',$pattern,$path);
            }
            
            $pattern = '#^' . $path . '$#';
            
            if(preg_match($pattern, $url , $params)){
                array_shift($params);
                
                
                self::$hasRoute = true;
                    //is_callable nesnenin çağrılabilir olmasında kullnılır
                if (is_callable($callback)){
                          echo call_user_func_array($callback,$params);
                }

                elseif(is_string($callback)){
                    
                    [$controllerName, $methodName] = explode('@', $callback); 
                     $controllerName = 'Jotform\App\Controllers\\' . $controllerName;
                     $controller = new $controllerName();
                     echo call_user_func_array([$controller,$methodName], $params);   
                }
            }
        }
        self::hasRoute();
    }

    //hasRoute()fonksiyonumu URL de yanlış yazım olduğunda 404 versin diye yazıyorum.
    public static function hasRoute()
    {
            if (self::$hasRoute===false){
                die('404 page not found');
            }
    }

    /**
     * @return string
     */
    public static function getMethod(): string
    {
         return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return string
     */
    //bu kullanım PHPdoc 'a uygun olduğu için kullanılır

    public static function getUrl(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function name($name){
        $key = array_key_last(self::$routes['get']);
        self::$routes['get'][$key]['name'] = $name;
    }
    /**
     * @param string $name
     * @param array $params
     * @return string
     */
    public static function url(string $name, array $params = []): string
    {
        $route = array_key_first(array_filter(self::$routes['get'], function ($route) use ($name) {
            return isset($route['name']) && $route['name'] === $name;
        }));
        return str_replace(array_keys($params), array_values($params),$route);
        //return getenv('BASE_PATH') . str_replace(array_map(fn($key) => ':' . $key, array_keys($params)), array_values($params), $route);
    }

    public static function prefix($prefix): Route
    {
        self::$prefix = $prefix;
        return new self();
    }

    public static function group(\Closure $closure):void
    {
        $closure();
        //fonksiyon çalıştıktan sonra içindeki işlemler yapılcak ardından gene prefix imizi boşa çıkarmamız gerekiyor
        self::$prefix = '';
    } 

}