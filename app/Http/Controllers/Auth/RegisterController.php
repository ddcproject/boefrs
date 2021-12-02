<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\BoeFrsController;
use App\User;
use DB;

class RegisterController extends BoefrsController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct()
	{
		parent::__construct();
		$this->middleware('guest');
	}

	protected function index(Request $request) {
		$provinces = parent::provinces();
		$roles = Role::pluck('name', 'id')->all();
		return view('auth.register', compact('roles'))
				->with('titleName', $this->title_name)
				->with('provinces', $provinces);
	}

	/**
	* Get a validator for an incoming registration request.
	*
	* @param  array  $data
	* @return \Illuminate\Contracts\Validation\Validator
	*/
	public function validator(array $data)
	{
		/* use for user model
		return Validator::make($data, [
			'province' => ['required'],
			'hospcode' => ['required'],
			'title_name' => ['required'],
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'min:6', 'confirmed'],
			'password_confirmation' => ['required', 'min:6'],
			'captcha' => ['required', 'captcha'],
		]);
		*/
	}

	/**
	* Create a new user instance after a valid registration.
	*
	* @param  array  $data
	* @return \App\User
	*/
	protected function create(array $data)
	{
		/* use for user model
		if (!isset($data['title_name_other']) || empty($data['title_name_other'])) {
			$data['title_name_other'] = NULL;
		}
		return User::create([
			'province' => $data['province'],
			'hospcode' => $data['hospcode'],
			'title_name' => $data['title_name'],
			'title_name_other' => $data['title_name_other'],
			'name' => $data['name'],
			'lastname' => $data['lastname'],
			'email' => $data['email'],
			'password' => Hash::make($data['password']),
		]);
		*/
	}

	protected function register(Request $request)
	{
		$this->validate($request, [
			'province' => 'required|numeric|min:0|not_in:0',
			'hospcode' => 'required|numeric|min:0|not_in:0',
			'title_name' => 'required|numeric|min:0|not_in:0',
			'name' => 'required|string|max:255',
			'email' => 'required|email|max:255|unique:users,email',
			'password' => 'required|same:confirm-password',
			'captcha' => 'required|captcha',
		]);
		$input = $request->all();
		if (!isset($input['title_name_other'])) {
			$input['title_name_other'] = NULL;
		}
		$input['password'] = Hash::make($input['password']);
		$user = User::create($input);
		$user->assignRole($request->input('roles'));
		return redirect()->route('register')->with('success', 'User created successfully');
	}

	public function getHospByProv(Request $request)
	{
		$this->result = parent::hospitalByProv($request->prov_id);
		$htm = "<option value=\"0\">-- โปรดเลือก --</option>\n";
		foreach($this->result as $key=>$value) {
				$htm .= "<option value=\"".$value->hospcode."\">".$value->hosp_name."</option>\n";
		}
		return $htm;
	}
}
