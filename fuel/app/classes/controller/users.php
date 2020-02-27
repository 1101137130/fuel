<?php

class Controller_Users extends Controller_Template
{public function before()
    {
    Lang::load(Session::get('lang'));
    return parent::before();
}
    public function action_login()
    {
        $data["subnav"] = array('login' => 'active');
        $auth = Auth::instance();

        $view = View::forge('users/login', $data);
        $form = Form::forge('login');
        $form->add('username', __('Username :'));
        $form->add('password', __('Password :'), array('type' => 'password'));
        $form->add('submit', '', array('type' => 'submit', 'value' => __('login')));
        if (Input::post()) {
            if ($auth->login(Input::post('username'), Input::post('password'))) {
                Auth::remember_me($auth->get_id());
                Session::set_flash(__('success'), __('loggedsuccess') . $auth->get_screen_name());

                Response::redirect('messages/');

            } else {

                Session::set_flash(__('error'), __('Userpasincorrect'));

            }
        }
        $view->set('reg', $form, false);
        $this->template->title = __('login');
        $this->template->content = $view;
    }
    public function action_chpass()
    {
        $data["subnav"] = array('Change' => 'active');
        $auth = Auth::instance();
        $view = View::forge('users/chpass', $data);
        $form = Form::forge('chpass');
        $form->add('Opassword', __('Old-password'), array('type' => 'password'))->add_rule(__('required'));
        $form->add('Npassword', __('New-password'), array('type' => 'password'))->add_rule(__('required'));
        $form->add('reNpassword', __('Re-try password'), array('type' => 'password'))->add_rule(__('required'));
        $form->add('submit', '', array('type' => 'submit', 'value' => __('Change')));

        if (Input::post()) {

            if ($auth->login($auth->get('username'), Input::post('Opassword')) and Input::post('Npassword') == Input::post('reNpassword')) {

                $result = $auth->change_password(Input::post('Opassword'), Input::post('Npassword'));
                if ($result) {

                    Session::set_flash(__('success'), __('passsuccechange'));
                    Response::redirect('users/manage');
                } else {
                    Session::set_flash(__('error'), __('passincorect'));
                    Response::redirect('users/login');
                }
            } else {
                Session::set_flash(__('error'), __('passincorect'));
                Response::redirect('users/login');

            }
        }

        $view->set('reg', $form, false);
        $this->template->title = 'Users &raquo; Change password';
        $this->template->content = $view;

    }
    public function get_edit()
    {

        $auth = Auth::instance();
        if (!is_null($auth)) {
            $this->getView('edit', null, null);

        } else {
            Session::set_flash(__('error'), __('Pleaselogin'));
            Response::redirect('users/manage');
        }
    }
    public function post_edit()
    {
        $auth = Auth::instance();
        $fieldset = Model_User::email_change_fieldset(Fieldset::forge('edit'));
        $fieldset->repopulate();
        $this->getView('edit', $fieldset, null);

    }
    public function action_manage()
    {

        $data["subnav"] = array('manage' => 'active');
        $id = Auth::instance()->get('id');
        //$username=Auth::instance()->get('username');

        if (!is_null($id)) {
            $user = Model_User::find($id);
            $view = View::forge('users/manage', $data);
            $view->set('username', $user->username, false);
            $view->set('email', $user->email, false);
            $view->set('id', $user->id, false);
            $this->template->title = __('manage');
            $this->template->content = $view;

        } else {
            echo "error";

        }
        $this->template->title = __('manage');
        $this->template->content = $view;
    }
    public function action_logout()
    {
        $auth = Auth::instance();
        $auth->logout();
        Session::set_flash(__('success'), __('Loggedout'));
        Response::redirect('messages/');

    }

    public function action_register($fieldset = null, $errors = null)
    {
        $this->getView('register', $fieldset, $errors);
    }
    //製作view $fieldset與$error可以不傳,傳了代表製作錯誤式判斷的view
    public function getView($title, $fieldset = null, $errors = null)
    {
        $data["subnav"] = array($title => 'active');
        $auth = Auth::instance();
        $view = View::forge('users/' . $title, $data);

        switch ($title) {
            case 'register':
                if (empty($fieldset)) {
                    $fieldset = Fieldset::forge('register');
                    Model_User::populate_register_fieldset($fieldset);
                } else {
                    $result = Model_User::validate_registration($fieldset, Auth::instance());
                    if ($result['e_found']) {
                        Session::set_flash(__('error'), $result['errors']);
                    } else {
                        Session::set_flash(__('success'), __('created'));
                        Auth::remember_me(Auth::get_id());
                        Response::redirect('./');
                    }
                }
                break;
            case 'edit':
                if (empty($fieldset)) {
                    $fieldset = Fieldset::forge('edit');
                    Model_User::email_change_fieldset($fieldset);
                } else {
                    $result = Model_User::validation_email($fieldset);
                    if ($result['e_found']) {
                        Session::set_flash(__('error'), $result['errors']);
                    } else { $result = $auth->login($auth->get('username'), Input::post('password'));
                        //如果email沒有問題
                        //執行檢查密碼是否與使用者符合
                        if ($result) { //符合就嘗試更改email
                            try {
                                $auth->update_user(array('email' => Input::post('email')));
                                Session::set_flash(__('success'), __('SuccessgullyChanged'));
                                Response::redirect('users/manage/' . $auth->get('id'));
                            } catch (Exception $e) {
                                $error = $e->getMessage();
                                Session::set_flash(__('error'), $error);
                                Response::redirect('users/manage/' . $auth->get('id'));

                            }
                        } else {
                            Session::set_flash(__('error'), __('passincorect'));
                            Response::redirect('/');
                        }
                    }
                }
                break;
        }
        if ($errors) {
            Session::set_flash(__('error'), $errors);
            //$view->set_safe('errors', $errors);
        }
        $view->set('reg', $fieldset->build(), false);
        $this->template->title = __($title);
        $this->template->content = $view;
    }

    public function get_register($fieldset = null, $errors = null)
    {
        $this->getView('register', $fieldset, $errors);
    }
    public function post_register()
    {

        $fieldset = Model_User::populate_register_fieldset(Fieldset::forge('register'));
        $fieldset->repopulate();
        $this->getView('register', $fieldset);

    }

}
