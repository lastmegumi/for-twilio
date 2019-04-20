<?php
class SmsController{
    function track(){
        if(!$_POST['SmsSid']){
            return;
        }
        $sms['sent_from']   =   $_POST['post.From'];
        $sms['sent_to']     =   $_POST['post.To'];
        $sms['sid']         =   $_POST['post.SmsSid'];
        $sms['status']      =   $_POST['post.SmsStatus'];
        $sms['apiVersion']    =  $_POST['post.ApiVersion'];
    }
}