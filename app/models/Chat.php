<?php

class Chat extends \Asatru\Database\Model {
    /**
     * @param $from
     * @return mixed
     * @throws \Exception
     */
    public static function fetchMessages($from = null)
    {
        try {
            if ($from === null) {
                return static::raw('SELECT * FROM (SELECT * FROM `@THIS` ORDER BY id DESC LIMIT 10) AS temp ORDER BY id ASC') ;
            } else {
                return static::raw('SELECT * FROM `@THIS` WHERE id > ? ORDER BY id ASC', [$from]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $username
     * @param $team
     * @param $message
     * @return void
     * @throws \Exception
     */
    public static function addMessage($username, $team, $message)
    {
        try {
            $token = md5(session_id());
            $username = trim($username);

            if (empty($username)) {
                throw new \Exception('Empty names are not allowed.');
            }

            if (Filter::check($username, 'username')) {
                throw new \Exception('The given name is not allowed.');
            }

            if (empty($team)) {
                throw new \Exception('Please join a team before chatting.');
            }

            if (empty($message)) {
                throw new \Exception('Empty messages are not allowed.');
            }

            if (Filter::check($message, 'message')) {
                throw new \Exception('Your message contains unwanted tokens.');
            }

            if (!static::isNameAvailable($username, $token)) {
                throw new \Exception('Your name is already taken. Please try again later.');
            }

            $prior = static::raw('SELECT * FROM `@THIS` WHERE token = ? ORDER BY id DESC LIMIT 1 ', [$token])->first();
            if ($prior) {
                if (strtolower($prior->get('username')) !== strtolower($username)) {
                    static::addNotice('User ' . $prior->get('username') . ' changed name to ' . $username);
                }
                
                if (strtolower($prior->get('team')) !== strtolower($team)) {
                    static::addNotice('User ' . $username . ' changed team to ' . strtoupper($team));
                }
            }

            static::raw('INSERT INTO `@THIS` (token, username, team, message) VALUES(?, ?, ?, ?)', [
                $token, $username, $team, $message
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $message
     * @return void
     * @throws \Exception
     */
    public static function addNotice($message)
    {
        try {
            $token = 'system';
            $username = 'system';
            $team = '';

            static::raw('INSERT INTO `@THIS` (token, username, team, message) VALUES(?, ?, ?, ?)', [
                $token, $username, $team, $message
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $username
     * @param $excltok
     * @return bool
     * @throws \Exception
     */
    public static function isNameAvailable($username, $excltok = null)
    {
        try {
            if ((!is_string($username)) || (empty($username))) {
                return false;
            }

            if ((is_string($excltok)) && (!empty($excltok))) {
                $namecount = static::raw('SELECT COUNT(*) AS `count` FROM `@THIS` WHERE username = ? AND token <> ?', [
                    $username, $excltok
                ])->first()->get('count');
            } else {
                $namecount = static::raw('SELECT COUNT(*) AS `count` FROM `@THIS` WHERE username = ?', [
                    $username
                ])->first()->get('count');
            }
            
            return (!($namecount > 0));
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public static function clearOldMessages()
    {
        try {
            static::raw('DELETE FROM `@THIS` WHERE created_at < NOW() - INTERVAL 1 DAY');
        } catch (\Exception $e) {
            throw $e;
        }
    }
}