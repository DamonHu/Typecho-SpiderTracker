<?php

/**
 * 蜘蛛来访跟踪插件，记录蜘蛛爬行的时间及其网址
 *
 * @package SpiderTrack
 * @author  DamonHu
 * @version 3.0.0
 * @update: 2024.03.14
 * @link https://ddceo.com/blog/1261.html
 */
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

class SpiderTrack_Plugin implements Typecho_Plugin_Interface
{
    public static function activate()
    {
        SpiderTrack_Util::activate();
    }

    public static function deactivate()
    {
        SpiderTrack_Util::deactivate();
    }

    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $bots = SpiderTrack_Util::defaultBotsList();

        $botList = new Typecho_Widget_Helper_Form_Element_Checkbox(
			'botList', $bots, ['baidu', 'google', 'sogou', 'youdao', 'soso', 'bing', 'yahoo', '360'],
          	'蜘蛛记录设置:', '请选择要记录的蜘蛛日志');

        $pageSize = new Typecho_Widget_Helper_Form_Element_Text(
            'pageSize',
            NULL,
            '20',
            '分页数量',
            '每页显示的日志数量'
        );

        $isDrop = new Typecho_Widget_Helper_Form_Element_Radio(
            'isDrop',
            array(
                '1' => '删除',
                '0' => '不删除'
            ),
            '0',
            '删除数据表',
            '请选择是否在禁用插件时，删除日志数据表'
        );
        $form->addInput($botList);
        $form->addInput($pageSize);
        $form->addInput($isDrop);
    }

    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
    }
}
