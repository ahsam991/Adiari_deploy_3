<?php

class Language {
    private static $current = 'ja'; // Default to Japanese
    private static $translations = [];
    private static $available = [
        'ja' => '日本語 (Japanese)',
        'en' => 'English',
        'ne' => 'नेपाली (Nepali)',
        'bn' => 'বাংলা (Bengali)'
    ];

    public static function init() {
        if (Session::has('lang')) {
            self::$current = Session::get('lang');
        }

        if (!array_key_exists(self::$current, self::$available)) {
            self::$current = 'ja';
        }

        self::load();
    }

    public static function load() {
        $file = ROOT_PATH . '/app/lang/' . self::$current . '/messages.php';
        if (file_exists($file)) {
            $loaded = require $file;
            self::$translations = is_array($loaded) ? $loaded : [];
        }

        // Merge with English fallback if current is not English
        if (self::$current !== 'en') {
            $enFile = ROOT_PATH . '/app/lang/en/messages.php';
            if (file_exists($enFile)) {
                $en = require $enFile;
                if (is_array($en)) {
                    self::$translations = array_merge($en, self::$translations);
                }
            }
        }
    }

    public static function get($key, $placeholders = []) {
        $text = self::$translations[$key] ?? $key;
        
        if (!empty($placeholders)) {
            foreach ($placeholders as $k => $v) {
                $text = str_replace(':' . $k, $v, $text);
            }
        }
        
        return $text;
    }

    public static function current() {
        return self::$current;
    }
    
    public static function label($lang = null) {
        $lang = $lang ?? self::$current;
        return self::$available[$lang] ?? $lang;
    }

    public static function available() {
        return self::$available;
    }
}
