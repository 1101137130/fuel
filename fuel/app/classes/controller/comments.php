<?php

class Controller_Comments extends Controller_Template
{

    public function action_edit($id = null)
    {
        $comment = Model_Comment::find($id);
        if (Input::post()) {

            $comment->comment = Input::post('comment');
            if ($comment->save()) {
                Session::set_flash(__('success'), __('Updatedcomment') . $id);
                Response::redirect('messages/view/' . $comment->message_id);
            } else {
                Session::set_flash(__('error'), __('Couldnotupdatecomment') . $id);
            }
        } else {
            $this->template->set_global('comment', $comment, false);
            $this->template->set_global('message', $comment->message_id, false);
        }
        $data["subnav"] = array('edit' => 'active');
        $data['form'] = View::forge('comments/_form');
        $this->template->title = 'Comments &raquo; Edit';
        $this->template->content = View::forge('comments/edit', $data);
    }

    public function action_create($id = null)
    {

        if (Input::post()) {
            $comment = Model_Comment::forge(array(
                'name' => Auth::instance()->get_screen_name(),
                'comment' => Input::post('comment'),
                'message_id' => Input::post('message_id'),
            ));
            if ($comment and $comment->save()) {
                Session::set_flash(__('success'), __('Addedcomment') . $comment->id . '.');
                Response::redirect('messages/view/' . $comment->message_id);
            } else {
                Session::set_flash(__('error'), _('Couldnotsavecomment'));
            }
        } else {
            $this->template->set_global('message', $id, false);
        }
        $data["subnav"] = array('create' => 'active');
        $data['form'] = View::forge('comments/_form');
        $this->template->title = 'Comments &raquo; Create';
        $this->template->content = View::forge('comments/create', $data);
    }
    public function action_delete($id)
    {
        $comment = Model_Comment::find($id);
        if ($comment) {
            $comment->delete();
            Session::set_flash(__('success'), __('Deletedcomment') . $id);
        } else {
            Session::set_flash(__('error'), __('Couldnotdeletecomment') . $id);
        }
        Response::redirect('messages/view/' . $comment->message_id);
    }
}
