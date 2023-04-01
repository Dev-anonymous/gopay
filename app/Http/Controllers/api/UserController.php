<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use stdClass;

class UserController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $user = auth()->user();
        $validator = Validator::make(request()->all(), [
            'name' => 'sometimes|string|max:45|min:6',
            'email' => 'sometimes|string|email|max:255|min:6|unique:users,email,' . $user->id,
            'phone' => 'sometimes|string|min:10|numeric|regex:/(\+)[0-9]{10}/|unique:users,phone,' . $user->id,
            'avatar' => 'sometimes|mimes:jpg,png,jpeg,gif|max:800|dimensions:min_width=300,min_height=300,max_width=500,max_height=500',
        ]);
        if ($validator->fails()) {
            return $this->error('Validation error', 400, ['errors_msg' => $validator->errors()->all()]);
        }
        $data = $validator->validate();
        if (request()->hasFile('avatar')) {
            $image = request()->file('avatar')->store('avatar', 'public');
            File::delete('storage/' . $user->avatar);
            $data['avatar'] = $image;
        }
        User::where('id', $user->id)->update($data);
        return $this->success("Vos données ont été mises à jour.");
    }

    public function update_pass()
    {
        $user = auth()->user();
        $validator = Validator::make(request()->all(), [
            'password' => 'required|string',
            'newpassword' => 'required|string|min:3|',
        ]);
        if ($validator->fails()) {
            return $this->error('Validation error', 400, ['msg' => $validator->errors()->all()]);
        }

        $cp = request()->password;
        $np = request()->newpassword;

        if (!(Hash::check($cp, $user->password))) {
            return $this->error('Validation error', 400, ['msg' => ['Le mot de passe actuel que vous avez saisi est incorrect.']]);
        }

        User::where('id', $user->id)->update(['password' => Hash::make($np)]);
        return $this->success("Votre mot de passe a été modifié.");
    }

    public function me()
    {
        $user = auth()->user();
        $user = User::where('id', $user->id)->first(['name', 'email', 'phone', 'avatar', 'user_role']);
        $user = (object) $user;
        $user->avatar = empty($user->avatar) ? asset('storage/default.png') : asset('storage/', $user->avatar);
        return $this->success("Profil", $user);
    }

    public function keys()
    {
        /** @var \App\Models\User $user **/
        $user = auth()->user();
        $keys = $user->apikeys()->get(['key', 'type']);
        return $this->success("Vos clés api", $keys);
    }

    public function feedback()
    {
        $validator = Validator::make(request()->all(), [
            'nom' => 'required|max:128',
            'email' => 'sometimes|email|max:128',
            'telephone' => 'sometimes|min:10|numeric|regex:/(\+)[0-9]{10}/|',
            'sujet' => 'required|min:6,max:255',
            'message' => 'required|min:6|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 400, ['errors_msg' => $validator->errors()->all()]);
        }

        if (empty(request()->telephone) and empty(request()->email)) {
            return $this->error('Validation error', 400, ['errors_msg' => ["Vous devez renseigner soit votre email soit votre numéro de téléphone."]]);
        }
        $data = $validator->validate();
        $data['date'] = now('Africa/Lubumbashi');
        Feedback::create($data);

        try {
            $d = implode('<br>', $data);
            $d = str_replace('<br>', "\n", $d);
            // Mail::to('contact@gooomart.com')->send(new ContactMail($d));
        } catch (\Throwable $th) {
        }
        return $this->success("Merci de nous avoir laisser votre message! nous le prenons avec beaucoup de considération et vous serez contacter si nécessaire. Merci.");
    }
}
