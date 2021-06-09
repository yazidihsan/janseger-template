<?php

namespace App\Http\Livewire;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;
use Livewire\Component;

class CreateUser extends Component
{
    use PasswordValidationRules;
    use WithFileUploads;
    public $user;
    public $userId;
    public $action;
    public $button;
    public $photo;

    protected function getRules()
    {
        $rules = ($this->action == "updateUser") ? [
            'user.email' => 'required|email|unique:users,email,',
            'user.address' => 'required|string',
            'user.houseNumber'  => 'required|string',
            'user.phoneNumber' => 'required|string|max:255',
            'user.city' => 'required|string|max:255',
            'user.roles' => 'required|string|max:255|in:USER,ADMIN'
             . $this->userId
        ] : [

            'user.password' => 'required|min:8|confirmed',
            'user.password_confirmation' => 'required' // livewire need this
        ];

        return array_merge([

            'user.name' => 'required|min:3',
            'user.email' => 'required|email|unique:users,email',

        ], $rules);
    }

    public function createUser ()
    {
        $this->resetErrorBag();
        $this->validate();



        $password = $this->user['password'];

        if ( !empty($password) ) {
            $this->user['password'] = Hash::make($password);
        }

        User::create($this->user);

        $this->emit('saved');
        $this->reset('user');
    }

    public function updateUser ()
    {
        $this->resetErrorBag();
        $this->validate();


        User::query()
            ->where('id', $this->userId)
            ->update([
                "name" => $this->user->name,
                "email" => $this->user->email,
                "address" => $this->user->address,
                "houseNumber" => $this->user->houseNumber,
                "phoneNumber" => $this->user->phoneNumber,
                "city" => $this->user->city,
                "roles" => $this->user->roles
            ]);

        $this->emit('saved');
    }

    public function submit()
    {
        $validatedData = $this->validate([
            'user.profile_photo_path' => 'required|image|max:2048'
        ]);



        $validatedData['user.profile_photo_path'] = request()->file('user.profile_photo_path')->store('assets/user', 'public');

        User::create($validatedData);

        $this->emit('saved');
        $this->reset('user');

    }

    public function mount ()
    {
        if (!$this->user && $this->userId) {
            $this->user = User::find($this->userId);
        }

        $this->button = create_button($this->action, "User");
    }

    public function render()
    {
        return view('livewire.create-user');
    }
}
