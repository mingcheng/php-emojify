<?php
/**
 *  在您的 Typecho 中使用 Emoji 表情，使用 emojify 定义的规则
 *
 * @package Emojify
 * @author 明城
 * @version 0.0.1
 * @link https://github.com/mingcheng/emojify.php
 */

require_once __DIR__ . "/Emojify.inc.php";

class Emojify_Plugin implements Typecho_Plugin_Interface
{
    public static $emojify;

    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return String
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = array('Emojify_Plugin', 'encode');
        Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx = array('Emojify_Plugin', 'encode');
        Typecho_Plugin::factory('Widget_Abstract_Comments')->filter = array('Emojify_Plugin', 'encode');
        return ('插件已经成功激活!');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return String
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate()
    {
        return ('插件已经被禁用');
    }
    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $enabled = new Typecho_Widget_Helper_Form_Element_Radio('enabled', array('1' => _t('开启'), '0' => _t('关闭')), '1', _t('开启 Emoji 转换'), _t('Emoji 表情总开关，开启以后会自动转换 Emoji 表情'));

        $html_entities = new Typecho_Widget_Helper_Form_Element_Radio('html_entities', array('1' => _t('开启'), '0' => _t('关闭')), '1', _t('HTML 实体'), _t('开启 HTML 实体转换，避免部分浏览器解析错误'));

        $form->addInput($enabled);
        $form->addInput($html_entities);
    }
    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {}

    /**
     * 转换 Emoji 表情
     *
     * @access public
     * @param $content
     * @param $class
     * @return $content
     */
    public static function encode($text, $widget, $lastResult)
    {
        $options = Typecho_Widget::widget('Widget_Options')->Plugin('Emojify');
        if ($options->enabled) {
            if (empty(self::$emojify)) {
                self::$emojify = new Emojify();
            }

            $text = empty($lastResult) ? $text : $lastResult;
            if (($widget instanceof Widget_Archive)) {
                $text = self::$emojify->encode($text, !!$options->html_entities);
            }

            if ($widget instanceof Widget_Abstract_Comments) {
                $text['text'] = self::$emojify->encode($text['text'], !!$options->html_entities);
            }
        }

        return $text;
    }
}
