<?php
/**
 * PHP Emojify - The PHP Port of Emojify
 *
 * @author mingcheng
 *
 * This script was inspired by https://github.com/mrowa44/emojify
 */

require_once __DIR__ . "/vendor/autoload.php";

class Emojify
{
    protected $encode = array();
    protected $decode = array();

    /**
     * Contruct from json file
     */
    public function __construct()
    {
        if (empty($this->encode) || empty($this->decode)) {
            $this->emojis = json_decode(file_get_contents(__DIR__ . "/data/emoji-huge.json"));
            foreach ($this->emojis as $emoji) {
                $name = $emoji->name;
                $code = $emoji->code;
                $this->encode[$name] = $code;
                $this->decode[$code] = $name;
            }
        }
    }

    public function getEmojiByName($name)
    {
        return $this->encode[$name] ?: null;
    }

    public function getNameByEmoji($emoji)
    {
        return $this->decode[$emoji] ?: null;
    }

    /**
     * Encode emoji
     */
    public function encode($text, $htmlEntities = false)
    {
        $that = $this;
        $func = function ($matches) use ($that, $htmlEntities) {
            if (isset($matches[0]) && !empty($emoji = $that->getEmojiByName($matches[0]))) {
                return $htmlEntities ? mb_convert_encoding($emoji, 'HTML-ENTITIES', 'UTF-8') : $emoji;
            } else {
                return $matches[0];
            }
        };

        return preg_replace_callback("/:[\w|_|-]+:/", $func, $text);
    }

    /**
     * Decode emoji
     */
    public function decode($emojis)
    {
        $that = $this;
        $func = function ($matches) use ($that) {
            if (isset($matches[0])) {
                return $that->getNameByEmoji($matches[0]);
            } else {
                return "";
            }
        };

        return preg_replace_callback('/([0-9#][\x{20E3}])|[\x{00ae}\x{00a9}\x{203C}\x{2047}\x{2048}\x{2049}\x{3030}\x{303D}\x{2139}\x{2122}\x{3297}\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', $func, $emojis);
    }
}
