<?php
namespace App;

class Api
{
	static $swoole;
	static function sendmail($to, $from, $title, $msg)
	{
		$model = createModel('UserMail');
		$put['title'] = $title;
		$put['content'] = $msg;
		$put['fid'] = $from;
		$put['tid'] = $to;
		return $model->put($put);
	}
	
	static function feed($type, $uid, $tid=0, $event_id=0)
	{
		return \Swoole::$php->model->Feeds->send($type, $uid, $tid, $event_id);
	}

    static function userInfoSafe(&$user)
    {
        unset($user['password'], $user['reg_ip'], $user['reg_time'], $user['lastip'], $user['lastlogin']);
    }

    static function addBlackList($uid, $wiki_id = 0, $remarks = '')
    {
        return table('wiki_blacklist')->put(['uid' => $uid, 'wiki_id' => $wiki_id, 'op_uid' => $_SESSION['user_id'], 'remarks' => $remarks]);
    }

    static function badUser($uid)
    {
        $backlist = table('wiki_blacklist');
        $banInfo = $backlist->get($uid, 'uid');
        if (!$banInfo->exist())
        {
            return false;
        }
        return $banInfo->get();
    }

    static function updateAvatarUrl(&$user, $https = false)
    {
        if (empty($user['avatar']))
        {
            $user['avatar'] = '/static/images/default.png';
        }
        if (substr($user['avatar'], 0, 4) != 'http')
        {
            $user['avatar'] = WEBROOT . $user['avatar'];
        }
        if ($https and substr($user['avatar'], 0, 5) != 'https')
        {
            $user['avatar'] = 'https'.substr($user['avatar'], 4);
        }
    }
}
