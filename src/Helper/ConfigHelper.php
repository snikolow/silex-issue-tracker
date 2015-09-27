<?php

namespace Tracker\Helper;

class ConfigHelper {

    public static function replaceParameters(&$value, $data, $userConfig) {
        if( preg_match('/\%([a-zA-Z_\.]+)\%/', $value, $matches) ) {
            $value = str_replace(
                $matches[0],
                self::getConfigItem($matches[1], $userConfig),
                $value
            );
        }
    }

    public static function getConfigItem($key, $data = null) {
        if( is_string($key) ) {
            if( strpos($key, '.') !== false ) {
                return self::getConfigitem( explode('.', $key), $data );
            }
            elseif( isset($data[ $key ])) {
                return $data[ $key ];
            }
        }

        if( is_array($key) && count($key) ) {
            if( count($key) === 1 && isset($data[ $key[0] ]) ) {
                return $data[ $key[0] ];
            }
            elseif( count($key) > 1) {
                $data = $data[ array_shift($key) ];

                return self::getConfigItem($key, $data);
            }
        }

        return null;
    }

    public function getContents($filename) {
        return file_get_contents(ROOT_PATH . '/app/config/' . $filename);
    }

}
