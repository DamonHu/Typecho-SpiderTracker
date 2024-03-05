<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

class RobotsPlusPlus_Util
{


    /**
     * 激活插件
     *
     * @return string
     */
    public static function activate()
    {
        Helper::addPanel(1, 'RobotsPlusPlus/Logs.php', '蜘蛛日志', '查看蜘蛛日志', 'administrator');
        Helper::addAction('robots-logs-edit', 'RobotsPlusPlus_Widget');
        Typecho_Plugin::factory('Widget_Archive')->header = array('RobotsPlusPlus_Util', 'isBot');
        self::install();
        return '插件启用成功。请进行<a href="options-plugin.php?config=RobotsPlusPlus">初始化设置</a>';
    }

    /**
     * 插件禁用
     *
     * @return string
     */
    public static function deactivate()
    {
        $config = Typecho_Widget::widget('Widget_Options')->plugin('RobotsPlusPlus');
        $db = Typecho_Db::get();
        $db->query($db->delete('table.options')->where('table.options.name = ? AND table.options.user = ?', 'license:RobotsPlusPlus', 0));
        $isDrop = $config->isDrop;
        Helper::removePanel(1, 'RobotsPlusPlus/Logs.php');
        Helper::removeAction('robots-logs-edit');
        if ($isDrop == 1) {
            $prefix = $db->getPrefix();
            $db->query("DROP TABLE `${prefix}robots_logs`", Typecho_Db::WRITE);
            return "插件已被禁用，数据表已未清除";
        }
        return "插件已被禁用，数据表未被清除";
    }

    /**
     * 数据库初始化
     *
     * @return void
     */
    public static function install()
    {
        $db = Typecho_Db::get();
        $adapter = $db->getAdapterName();
        $robots = $db->getPrefix() . "robots_logs";
        if ("Pdo_SQLite" === $adapter || "SQLite" === $adapter) {
            $db->query(" CREATE TABLE IF NOT EXISTS " . $robots . " (
                        lid INTEGER PRIMARY KEY,
                        bot TEXT,
                        url TEXT,
                        ip TEXT,
                        ltime INTEGER)");
        }
        if ("Pdo_Mysql" === $adapter || "Mysql" === $adapter) {
            $db->query("CREATE TABLE IF NOT EXISTS " . $robots . " (
                        `lid` int(10) unsigned NOT NULL auto_increment,
                        `bot` varchar(16) default NULL,
                        `url` varchar(128) default NULL,
                        `ip` varchar(128) default NULL,
                        `ltime` int(10) unsigned default '0',
                        PRIMARY KEY  (`lid`)
                    ) DEFAULT CHARSET=utf8mb4; AUTO_INCREMENT=1");
        }
    }

    /**
     * 蜘蛛记录函数
     *
     * @param mixed $rule
     * @return boolean
     */
    public static function isBot($rule = NULL)
    {
        $botList = self::getBotsList();
        $bot = NULL;
        if (count($botList) > 0) {
            $request = Typecho_Request::getInstance();
            $useragent = strtolower($request->getAgent());
            foreach ($botList as $key => $value) {
                if (strpos($useragent, strval($key)) !== false) {
                    $bot = $key;
                    break;
                }
            }
            if (!empty($bot)) {
                // 插入结构
                $uri = $request->getRequestUri();
                $struct = array(
                    'bot' => $bot,
                    'url' => strlen($uri) > 128 ? _t('URI超长，请扩充数据库字段') : $uri,
                    'ip' => self::getRealIp(),
                    'ltime' => self::getTimeStamp(),
                );

                $db = Typecho_Db::get();
                $db->query($db->insert('table.robots_logs')->rows($struct));
                return false;
            }
        }
        return false;
    }

    /**
     * 获取真实IP
     *
     * @return String
     */
    public static function getRealIp()
    {
        $widget = Typecho_Request::getInstance();
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $forward = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = $forward[0];
        } else {
            $ip = $widget->getIp() === NULL ? 'UnKnown' : $widget->getIp();
        }
        return $ip;
    }

    /**
     * 获取蜘蛛列表
     *
     * @return array
     */
    public static function getBotsList()
    {
        $bots = array();
        $_bots = explode("|", str_replace(array("\r\n", "\r", "\n"), "|", self::getConfig()->botList));
        foreach ($_bots as $_bot) {
            $_bot = explode("=>", $_bot);
            $bots[strval($_bot[0])] = $_bot[1];
        }
        return $bots;
    }

    /**
     * 获取时间戳
     *
     * @return int
     */
    public static function getTimeStamp()
    {
        $options = Typecho_Widget::widget('Widget_Options');
        $timeStamp = $options->gmtTime;
        $offset = $options->timezone - $options->serverTimezone;
        return $timeStamp + $offset;
    }

    /**
     * 湖区插件配置
     *
     * @return Widget_Options
     */
    public static function getConfig()
    {
        return Typecho_Widget::widget('Widget_Options')->plugin('RobotsPlusPlus');
    }
}
