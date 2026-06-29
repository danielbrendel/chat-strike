<?php

class ChatController extends BaseController {
    /**
	 * Handles URL: /chat/message
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function message($request)
	{
		try {
            $username = $request->params()->query('username');
            $team = $request->params()->query('team');
            $message = $request->params()->query('message');

            Chat::addMessage($username, $team, $message);

            Activity::hit();

            return json([
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
	}

    /**
	 * Handles URL: /chat/fetch
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function fetch($request)
	{
		try {
            $from = $request->params()->query('from');
            $result = [];
            $messages = Chat::fetchMessages($from);

            foreach ($messages as $msg) {
                $result[] = [
                    'id' => $msg->get('id'),
                    'username' => $msg->get('username'),
                    'team' => $msg->get('team'),
                    'message' => $msg->get('message'),
                    'context' => ($msg->get('token') === 'system') ? 'system' : 'user',
                    'date' => date('Y-m-d H:i:s', strtotime($msg->get('created_at')))
                ];
            }

            Activity::hit();

            return json([
                'code' => 200,
                'messages' => $result
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
	}

    /**
	 * Handles URL: /chat/online
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function online($request)
	{
		try {
            $count = Activity::online();

            Activity::hit();

            return json([
                'code' => 200,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
	}

    /**
	 * Handles URL: /chat/clear
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function clear($request)
	{
		try {
            Chat::clearOldMessages();

            Activity::hit();

            return json([
                'code' => 200
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 500,
                'msg' => $e->getMessage()
            ]);
        }
	}
}
    