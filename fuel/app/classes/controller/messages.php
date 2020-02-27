<?php
class Controller_Messages extends Controller_Template
{
    public function before()
    {
        if (!Session::get('lang')) {
            Session::set('lang', 'en');
        }
        Lang::load(Session::get('lang'));
        return parent::before();

    }
    public function action_lang()
    {
        Session::set('lang', $_POST['val']);
        //Lang::delete(Lang::get_lang());
        Response::redirect('messages');
        //Config::set('language',Input::post('language'));

    }
    public function action_index()
    {

        $messages = Model_Message::find('all');
        $comment_links = array();
        foreach ($messages as $message) {
            $results = DB::select()
                ->from('comments')
                ->where('message_id', $message->id)
                ->execute();

            $count = count($results);

            if ($count == 0) {
                $comment_links[$message->id] = 'View';
            } else {
                $comment_links[$message->id] = $count . ' ' . Inflector::pluralize('Comment', $count);
            }

        }
        $view = View::forge('messages/index');
        $view->set('comment_links', $comment_links);
        $view->set('messages', $messages);
        $this->template->title = __('Messages');
        $this->template->content = $view;

    }

    public function action_view($id = null)
    {

        is_null($id) and Response::redirect('messages');

        if (!$message = Model_Message::find($id)) {
            $errormes = $this->output = __('error');
            $couldnotfindmes = $this->output = __('couldnotfindmes');
            Session::set_flash($errormes, $couldnotfindmes . '#' . $id);
            Response::redirect('messages');
        }
        $comments = Model_Comment::find('all', array('where' => array('message_id' => $id)));

        $data = array(
            'message' => $message,
            'comments' => $comments,
        );

        $this->template->title = $this->output = __('Message');
        $this->template->content = View::forge('messages/view', $data);

    }

    public function action_create()
    {

        if (Input::method() == 'POST') {
            $val = Model_Message::validate('create');
            if ($val->run()) {
                $message = Model_Message::forge(array(
                    'name' => Auth::instance()->get_screen_name(),
                    'messages' => Input::post('messages'),
                ));

                if ($message and $message->save()) {
                    Session::set_flash(__('success'), __('Addedmessage') . $message->id . '.');

                    Response::redirect('messages');
                } else {
                    Session::set_flash(__('error'), __('Couldnotsavemessage'));
                }
            } else {
                Session::set_flash(__('error'), $val->error());
            }
        }

        $this->template->title = __('Messages');
        $this->template->content = View::forge('messages/create');

    }

    public function action_edit($id = null)
    {

        is_null($id) and Response::redirect('messages');

        if (!$message = Model_Message::find($id)) {
            Session::set_flash(__('error'), __('Couldnotfindmessage') . $id);
            Response::redirect('messages');
        }

        $val = Model_Message::validate('edit');

        if (Input::post()) {

            $message->messages = Input::post('messages');

            if ($message->save()) {
                Session::set_flash(__('success'), __('Updatedmessage') . $id);
                Response::redirect('messages');
            } else {
                Session::set_flash(__('error'), __('Couldnotupdatemessage') . $id);
            }
        } else {
            if (Input::method() == 'POST') {
                $message->name = $val->validated('name');
                $message->messages = $val->validated('messages');

                Session::set_flash('error', $val->error());
            }

            $this->template->set_global('message', $message, false);
        }

        $this->template->title = __('Messages');
        $this->template->content = View::forge('messages/edit');

    }

    public function action_delete($id = null)
    {
        is_null($id) and Response::redirect('messages');

        if ($message = Model_Message::find($id)) {
            $message->delete();

            Session::set_flash(__('success'), __('Deletedmessage') . $id);
        } else {
            Session::set_flash(__('error'), __('Couldnotdeletemessage') . $id);
        }

        Response::redirect('messages');

    }

}
