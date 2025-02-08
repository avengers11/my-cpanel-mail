<?php

namespace App\Lib;

use App\Lib\CpanelUAPI;
use Illuminate\Support\Facades\Auth;
/*
 * Cpanel UAPI Calls
 * https://api.docs.cpanel.net/cpanel/introduction/

 * */
class HmailAPI
{
    /**
     * @return mixed|null
     */
    public static function domain(){
        $user = Auth::user();
        return $user->domain;
    }

    /**
     * @return CpanelUAPI
     */
    public static function cpanel(){
        $user = Auth::user();
        return new CpanelUAPI($user->cpanle_username, $user->cpanle_password, self::domain());
    }

    /**
     * Create email address
     * @param $email
     * @param $password
     * @param $quota
     * @param $forward
     * @return mixed
     */
    public static function addEmail($email, $password, $quota=100){
        $resp = self::cpanel()->uapi->Email->add_pop([
            'email' => $email,
            'password' => $password,
            'quota' => $quota
        ]);

        return $resp;
    }

    /**
     * Change email password
     * @param $email
     * @param $password
     * @return mixed
     */
    public static function updateEmailPassword($email, $password){
        $resp = self::cpanel()->uapi->Email->passwd_pop([
            'email' => $email,
            'password' => $password,
            'domain'=>self::domain()
        ]);
        if(isset($resp->status) and $resp->status ){
            return true;
        }
        return false;
    }

    /**
     * List email account with disk info
     * @return mixed
     */
    public static function listEmails(){

        if(env('APP_ENV')==='local'){
            $resp = json_decode(file_get_contents(__DIR__.'/../../tests/emails.json'), true);
        }else{
            $emails = self::cpanel()->uapi->Email->list_pops_with_disk([
                'domain'=>self::domain(),
                'maxaccounts'  => '5000'
            ]);
            $resp = json_decode(json_encode($emails), true);
        }
        return $resp;
    }

    /**
     * Get email
     * @param $email
     * @return mixed
     */
    public static function getEmail($email){
        $username = strstr($email, '@', true);
        $resp = self::cpanel()->uapi->Email->list_pops_with_disk([
            'user'=>$username,
            'domain'=>self::domain()
        ]);
        return $resp;
    }

    /**
     * Delete email
     * @param $email
     * @return mixed
     */
    public static function deleteEmail($email){
        $resp = self::cpanel()->uapi->Email->delete_pop([
            'email' => $email,
            'domain'=>self::domain()
        ]);
        if(isset($resp->status) and $resp->status ){
            return true;
        }
        return false;
    }

    /**
     * Add forwarding
     * @param $email
     * @return mixed
     */
    public static function addForward($email, $email_forward){
        $resp = self::cpanel()->uapi->Email->add_forwarder([
            'email' => $email,
            'fwdopt' => 'fwd',
            'fwdemail' => $email_forward,
            'domain' => self::domain()
        ]);
        if(isset($resp->status) and $resp->status){
            return true;
        }
        return false;
    }

    /**
     * Get forwarding
     * @param $email
     * @return mixed
     */
    public static function getForward($email){
        if(env('APP_ENV')==='local') {
            $resp = json_decode(file_get_contents(__DIR__.'/../../tests/fowarders.json'),true);
        }
        else{
            $forwards = self::cpanel()->uapi->Email->list_forwarders([
                'domain' => self::domain(),
                'regex' => $email,
            ]);
            $resp = json_decode(json_encode($forwards), true);
        }
        return $resp;
    }
    /**
     * Delete forwarding
     * @param $email
     * @return mixed
     */
    public static function deleteForward($email, $forwarder){
        $resp = self::cpanel()->uapi->Email->delete_forwarder([
            'address' => $email,
            'forwarder' => $forwarder,
            'domain'=>self::domain()
        ]);
        if(isset($resp->status) and $resp->status ){
            return true;
        }
        return false;
    }

    /**
     * Edit Quota
     * @param $email
     * @param $quota
     * @return mixed
     */
    public static function editQuota($email,$quota){
        $username = strstr($email, '@', true);
        $resp = self::cpanel()->uapi->Email->edit_pop_quota([
            'email' => $username,
            'quota' => $quota,
            'domain'=>self::domain()
        ]);
        if(isset($resp->status) and $resp->status ){
            return true;
        }
        return false;
    }

    /**
     * Get Auto Responder
     * @param $email
     * @return mixed
     */
    public static function getAutoResponder($email){
        if(env('APP_ENV')==='local') {
            $resp = json_decode(file_get_contents(__DIR__.'/../../tests/autoresponder.json'),true);
        }
        else{
            $responder = self::cpanel()->uapi->Email->get_auto_responder([
                'email' => $email,
            ]);
            $resp = json_decode(json_encode($responder), true);
        }
        return $resp;
    }

    /**
     * Add Auto Responder
     * @param $email
     * @param $from
     * @param $subject
     * @param $body
     * @param $is_html
     * @param $interval
     * @param $start
     * @param $stop
     * @return bool
     */
    public static function addAutoResponder($email, $from, $subject, $body, $is_html, $interval, $start, $stop){
        $resp = self::cpanel()->uapi->Email->add_auto_responder([
            'email' => $email,
            'from' => $from,
            'subject' => $subject,
            'body' => $body,
            'is_html' => $is_html,
            'interval' => $interval,
            'start' => $start,
            'stop' => $stop,
            'domain' => self::domain()
        ]);
        if(isset($resp->status) and $resp->status){
            return true;
        }
        return false;
    }

    /**
     * Delete Auto Responder
     * @param $email
     * @return bool
     */
    public static function deleteAutoResponder($email){
        $resp = self::cpanel()->uapi->Email->delete_auto_responder([
            'email' => $email,
        ]);
        if(isset($resp->status) and $resp->status ){
            return true;
        }
        return false;
    }
}