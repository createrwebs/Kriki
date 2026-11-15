<?php
if (!class_exists('App', false)) {
    class App {
        public static function __callStatic($name, $arguments) {
            try {
                if (class_exists('DI') && \DI::has('app')) {
                    $app = \DI::make('app');
                    if (is_object($app) && method_exists($app, $name)) {
                        return $app->$name(...$arguments);
                    }
                }
            } catch (\Throwable $e) {}

            try {
                if (method_exists('WHMCS\Application', 'getInstance')) {
                    $app = \WHMCS\Application::getInstance();
                    if (is_object($app) && method_exists($app, $name)) {
                        return $app->$name(...$arguments);
                    }
                }
            } catch (\Throwable $e) {}

            if ($name === 'getSystemURL') {
                return 'http://47.84.242.200/billing/';
            }
            if ($name === 'getSystemDatabaseURL') {
                return 'http://47.84.242.200/billing/';
            }
            if ($name === 'getSystemSSLURL') {
                return 'http://47.84.242.200/billing/';
            }
            return null;
        }
    }
}
