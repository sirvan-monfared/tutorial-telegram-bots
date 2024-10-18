<?php

namespace App\Http\Controllers\Admin;

use App\Core\Session;
use App\Core\Validator;
use App\Http\Controllers\BaseController;
use App\Models\User;

class UserController extends BaseController
{
    public function index()
    {
        $this->view('admin.user.index', [
            'users' => (new User())->all()
        ]);
    }

    public function create()
    {
        $this->view('admin.user.create', [
            'user' => (new User)
        ]);
    }

    public function edit(int $id)
    {

        $user = (new User)->findOrFail($id);

        $this->view('admin.user.edit', [
            'user' => $user
        ]);
    }

    public function store()
    {
        $data = $_POST;

        $validator = new Validator($data, [
            'name' => ['required'],
            'chat_id' => ['required']
        ]);

        try {
            $validator->validate();

            $user = (new User)->create([
                'name' => $data['name'],
                'chat_id' => $data['chat_id'],
                'address' => $data['address'],
            ]);

            Session::flash('success', 'user created Successfully');

            redirectTo(route('admin.user.index'));

        } catch (\Exception $e) {
            $this->redirectToForm($validator);
        }
    }

    public function update(int $id)
    {
        $user = (new User)->findOrFail($id);

        $data = $_POST;

        $validator = new Validator($data, [
            'name' => ['required'],
            'chat_id' => ['required']
        ]);

        try {
            $validator->validate();

           $user->update([
                'name' => $data['name'],
                'chat_id' => $data['chat_id'],
                'address' => $data['address'],
            ]);

            Session::flash('success', 'user Updated Successfully');

            redirectBack();

        } catch (\Exception $e) {
            $this->redirectToForm($validator);
        }
    }

    public function destroy(int $id)
    {
        $user = (new User)->findOrFail($id);

        $user->delete();

        Session::flash('success', 'User Deleted');

        redirectBack();
    }
}