<?php

/**
 * 蜘蛛来访日志插件，记录蜘蛛爬行的时间及其网址
 *
 * @package RobotsPlusPlus
 * @author  Ryan, YoviSun, Shion
 * @version 2.0.6
 * @update: 2021.08.14
 * @link http://doufu.ru
 */
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

class RobotsPlusPlus_Plugin implements Typecho_Plugin_Interface
{
    public static function activate()
    {
        RobotsPlusPlus_Util::activate();
    }

    public static function deactivate()
    {
        RobotsPlusPlus_Util::deactivate();
    }

    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $bots = array(
            'baidu=>百度',
            'google=>谷歌',
            'sogou=>搜狗',
            'youdao=>有道',
            'soso=>搜搜',
            'bing=>必应',
            'yahoo=>雅虎',
            '360=>360搜索'
        );

        $botList = new Typecho_Widget_Helper_Form_Element_Textarea('botList', null, implode("\n", $bots), _t('蜘蛛记录设置'), _t('请按照格式填入蜘蛛信息，英文关键字不能超过16个字符'));

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
