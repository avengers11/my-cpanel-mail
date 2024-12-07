<?php namespace App\Lib;
/**
 * Config
 *
 * @author    Hezekiah O. <support@hezecom.com>
 */
class Config
{
    private static $config;

    /**
     * @param $key
     * @param $default
     * @return mixed|null
     */
    public static function get($key, $default = null)
    {
        if (is_null(self::$config)) {
            self::$config = require_once(__DIR__ . '/../../config/app.php');
        }
        return !empty(self::$config[$key]) ? self::$config[$key] : $default;
    }

    /**
     * @param $data
     * @return bool|void
     */
    public static function appSettings($data=[],$apiKey=null){
        $appKey = base64_encode(md5(uniqid(rand(), true)));
        if ($data['CPANEL_DOMAIN'] and $data['CPANEL_USERNAME'] and $data['CPANEL_PASSWORD']) {
            $disc = $data['CPANEL_QUOTA']??500;
            try {
                $environ = "APP_NAME='CPEM'
APP_ENV=production
APP_KEY=" .$appKey. "
AUTH_KEY=" .$apiKey. "
APP_URL=" . base_url() . "

DB_DRIVER=sqlite
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

# cpanel settings
CPANEL_USERNAME=" .$data['CPANEL_USERNAME']. "
CPANEL_PASSWORD=" .encrypt($data['CPANEL_PASSWORD'],$apiKey)."
CPANEL_DOMAIN=" .$data['CPANEL_DOMAIN']. "
CPANEL_QUOTA=".$disc."

#mailer settings

MAIL_DRIVER=sendmail
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS='info@".$data['CPANEL_DOMAIN']."'
MAIL_FROM_NAME='".$data['APP_NAME']."'

";
                file_put_contents(__DIR__ . '/../../.env', $environ);
                return true;
            } catch (\Exception $ex) {
                return false;
            }
        }
    }
}
