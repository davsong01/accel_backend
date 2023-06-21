<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\RolePermission;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Symfony\Component\VarDumper\Caster\LinkStub;

class MenuController extends Controller
{
    private  $individual_permissions;
    private $admin_id;
    private $admin_roles;
    private $customer_id;
    private $customer_roles;
    private $company_id;
    private $company_name;
    private $company_url;
    private $company_location;

    public function __construct(Request $request){
        // $this->individual_permissions = $request->session('individual_permissions');
        // $this->admin_id = Session::get('admin_id');
        // $this->admin_roles = Session::get('admin_roles');
        // $this->customer_id = Session::get('customer_id');
        // $this->customer_roles = Session::get('customer_roles');
        // $this->company_id = Session::get('company_id');
        // $this->company_name = Session::get('company_name');
        // $this->company_url = Session::get('company_url');
        // $this->company_location = Session::get('company_location');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$status=null)
    {
        $individual_permissions = Session::get('individual_permissions');

        $parents = RolePermission::where(['parent' => 0])->orderBy('order', 'ASC')->get();
        $menus = RolePermission::orderBy('parent', 'asc')->orderBy('order', 'asc')->get();
        dd(Session::all(), 'asas');
        $user_roles = auth()->user()->roles; // You can 
        dd($user_roles);
        foreach ($menus as $parent) {
            $rolex = [];
            $roleIds = [];
            $roles = isset($parent->role_ids) ? explode(',',$parent->role_ids):[];
            
            if (count($roles) > 0) {
                foreach ($roles as $role) {
                    $rrr = Role::where('id', $role)->first();
                    $rolex[] = $rrr->name;
                    $roleIds[] = $rrr->id;
                }
            }
            $parent['roles'] = $rolex;
            $parent['roleIds'] = $roleIds;
        }
        
        $roles = Role::where(['type' => 'admin'])->get();

        return response()->json(['data' => $menus, 'message' => 'success'], 200);
       
    }

    public function generateLinks($linkid, $link_name, $link_route, $link_slug, $link_icon,$description, $parent_id)
    {
        $individual_permissions = Session::get('individual_permissions') ?? [];
        $user_role_ids = Session::get('admin_roles');
        $links = [];
        
        if (Session::has('admin_id')) {
            $getChildren = RolePermission::where(['status' => 'active', 'is_menu' => 'yes', 'parent' => $linkid, 'type' => 'admin'])->orderBy('order', 'ASC')->get();
            
            $getChildren = $getChildren->filter(function ($query) use ($user_role_ids, $individual_permissions) {
                $query->role_ids = (isset($query->role_ids) && !empty($query->role_ids)) ? explode(',', $query->role_ids) : [];
                foreach ($user_role_ids as $role) {
                    if (in_array($role, $query->role_ids) || in_array($query->id, $individual_permissions)) {
                        return $query;
                    }
                }
            });
        }

        if (count($getChildren) > 0) {
            foreach ($getChildren as $key => $value) {
                $perms = $this->checkUserHasRightToPage($value->slug, $user_role_ids);
                if ($perms === true) {
                    $linko[] = $this->generateLinks($value->id, $value->menu_name, $value->route, $value->slug, $value->icon,$value->description,$value->parent);
                } else {
                    $linko[] = '';
                }
            }
          
            $sub_links = $linko;
            
            $links = [
                'id' => $linkid,
                'name' => $link_name,
                'icon' => $link_icon,
                'link' => Route::has($link_route) ? route($link_route) : '',
                'slug' => $link_slug,
                'description' => $description,
                'children' => $sub_links
            ];

        } else {
            if (\Route::has($link_route)) {
                $links = [
                    'id' => $linkid,
                    'parent_id' => $parent_id,
                    'name' => $link_name,
                    'icon' => $link_icon,
                    'link' => Route::has($link_route) ? route($link_route) : '',
                    'slug' => $link_slug,
                    'description' => $description
                ];
            } else {
                $links = [
                    'id' => $linkid,
                    'parent_id' => $parent_id,
                    'name' => $link_name,
                    'icon' => $link_icon,
                    'link' => Route::has($link_route) ? route($link_route) : '',
                    'slug' => $link_slug,
                    'description' => $description
                ];
            }
        }

        return $links;
    }
    public function linkGenerator()
    {
        $links = [];
        $individual_permissions = Session::get('individual_permissions') ?? [];
        $admin_roles = Session::get('admin_roles');

        if(Session::has('admin_id')){
            $links = RolePermission::where(['status' => 'active', 'is_menu' => 'yes', 'parent' => NULL, 'type' => 'admin'])->orderBy('order', 'ASC')->get();
            $links = $links->filter(function ($query) use ($admin_roles, $individual_permissions) {
                $query->role_ids = (isset($query->role_ids) && !empty($query->role_ids)) ? explode(',', $query->role_ids) : [];
                
                foreach ($admin_roles as $role) {
                    if (in_array($role, $query->role_ids)|| in_array($query->id, $individual_permissions)) {
                        return $query;
                    } 
                }
            });
        }
        
        $lo = [];
       
        foreach ($links as $key => $value) {
            $perms = $this->checkUserHasRightToPage($value->slug, $admin_roles);

            if ($perms === TRUE) {
                // if ($perms === TRUE && $perm_status === TRUE) {
                $lox[] = $value->link_name;
                $lo[] = $this->generateLinks($value->id, $value->menu_name, $value->route, $value->slug, $value->icon, $value->description,$value->parent);
            }
        }

        return response()->json(['message' => 'success', 'data' => $lo], 200);
    }

    public function singleLinkGenerator($parent_id)
    {
        $links = [];
        $individual_permissions = Session::get('individual_permissions') ?? [];
        $admin_roles = Session::get('admin_roles');
        $all_roles = array_unique(array_merge($individual_permissions, $admin_roles));
        if (Session::has('admin_id')) {
            $links = RolePermission::where(['status' => 'active', 'is_menu' => 'yes', 'id' => $parent_id, 'type' => 'admin'])->orderBy('order', 'ASC')->first();
            if(!$links){
                return;
            }

            $link_role_ids = (isset($links->role_ids) && !empty($links->role_ids)) ? explode(',', $links->role_ids) : [];
            $allowed = array_intersect($link_role_ids, $all_roles);

            if(!$allowed && count($allowed) <= 0){
                return response()->json(['error' => 'You do not have access to this resource'], 403);
            }

        }

        $lo = [];
        $lo = $this->generateLinks($links->id, $links->menu_name, $links->route, $links->slug, $links->icon, $links->description, $links->parent);
       
        return response()->json(['message' => 'success', 'data' => $lo], 200);
    }

    public function checkUserHasRightToPage($code, $user_class)
    {
        return true;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $data = $this->validate($request,[
            "name" => "required|string",
            "email" => "required|unique:users,email",
            "phone" => "required",
            "roles" => "sometimes",
            "status" => "required",
            "password" => "required"
        ]);

        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'phone'=>$data['phone'],
            'password'=>bcrypt($data['password']),
        ]);

        $admin=Admin::create([
            'user_id'=>$user->id,
            'status'=>$data['status']
        ]);

        if($request->has('roles')){
            $admin->roles()->sync(array_values($data['roles']));
        }

        if($this->settings->send_sms == 'yes'){
            $message = $this->getCreateAdminSms($user, $data['password']);
            $this->logMessages('sms',$user->phone,$message,$data['status'],null,auth()->user()->admin->id);
        }

        if ($this->settings->send_email == 'yes') {
            $subject = 'You are now an Admin';
            $message = $this->getCreateAdminEmail($user, $data['password']);
            $this->logMessages('email', $user->email, $message, $data['status'], $subject, auth()->user()->admin->id);
        }
       
        return back()->with('message', 'Admin Added successfully');

    }

    public function show(RolePermission $rolePermission)
    {
        //
    }


    public function edit(RolePermission $rolePermission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RolePermission  $rolePermission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $permission = RolePermission::where('id',$request->permission_id)->first();

        $data['status'] = $request->status;
        $data['link_name'] = $request->link_name;
        $data['order'] = $request->order;
        $data['icon'] = $request->icon;
        $data['parent'] = $request->parent == 'none' ? 0 : $request->parent;
        $data['role_ids'] = isset($request->role_ids) ? implode(',', $request->role_ids) : NULL;
        
        $permission->update($data);

        return back()->with('message', 'Update succesful');
    }

    public function destroy($id)
    {
        
    }

}
