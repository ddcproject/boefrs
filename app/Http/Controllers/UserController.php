<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\BoeFrsController;
use App\User;
use DB;
use Hash;

class UserController extends BoeFrsController
{
	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->middleware(['role:admin|hospital|lab|hosp-group']);
		$this->middleware('permission:manageuser|list|create|edit|delete', ['only' => ['index','store']]);
	}

	public function index(Request $request) {
		$data = User::orderBy('id', 'DESC')->paginate(5);
		return view('users.index', compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);
	}

	public function create() {
		$roles = Role::pluck('name', 'name')->all();
		return view('users.create', compact('roles'))->with('titleName', $this->title_name);
	}

	public function store(Request $request) {
		$this->validate($request, [
			'province' => 'required',
			'hospcode' => 'required',
			'name' => 'required',
			'email' => 'required|email|unique:users,email',
			'password' => 'required|same:confirm-password',
			'roles' => 'required'
		]);
		$input = $request->all();
		if (empty($input['title_name']) || is_null($input['title_name'])) {
			$input['title_name'] = null;
			$input['title_name_other'] = null;
		} elseif ($input['title_name'] == 6) {
			$input['title_name'] = $input['title_name'];
			$input['title_name_other'] = $input['title_name_other'];
		} else {
			$input['title_name'] = $input['title_name'];
			$input['title_name_other'] = null;
		}

		$input['password'] = Hash::make($input['password']);
		$user = User::create($input);
		$user->assignRole($request->input('roles'));
		return redirect()->route('users.index')->with('success', 'User created successfully');
	}

	public function show($id) {
		$user = User::find($id);
		return view('users.show', compact('user'));
	}

	public function edit($id) {
		$user = User::find($id);
		$roles = Role::pluck('name', 'name')->all();
		$userRole = $user->roles->pluck('name', 'name')->all();
		return view('users.edit', compact('user', 'roles', 'userRole'));
	}

	public function update(Request $request, $id) {
		$this->validate($request, [
			//'province' => 'required',
			//'hospcode' => 'required',
			'name' => 'required',
			'email' => 'required|email|unique:users,email,'.$id,
			'password' => 'same:confirm-password',
			'roles' => 'required'
		]);

		$input = $request->all();
		if (!empty($input['password'])) {
			$input['password'] = Hash::make($input['password']);
		} else {
			$input = array_except($input, array('password'));
		}

		$user = User::find($id);
		$user->update($input);
		DB::table('model_has_roles')->where('model_id',$id)->delete();

		$user->assignRole($request->input('roles'));

		return redirect()->route('users.index')->with('success', 'User updated successfully');
	}

	public function destroy($id) {
		User::find($id)->delete();
		return redirect()->route('users.index')->with('success', 'User deleted successfully');
	}

	public function ajaxGetHospByProv(Request $request) {
		$this->result = parent::hospitalByProv($request->prov_id);
		$htm = "<option value=\"0\">-- โปรดเลือก --</option>\n";
		foreach($this->result as $key=>$value) {
				$htm .= "<option value=\"".$value->hospcode."\">".$value->hosp_name."</option>\n";
		}
		return $htm;
	}
}
