<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Staff;
use App\Models\Package;
use App\Models\StaffRole;
use Illuminate\Http\Request;
use App\Models\RolePermission;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Symfony\Component\VarDumper\Caster\LinkStub;

class MenuController extends Controller
{
    private  $individual_permissions;
    private $staff_id;
    private $staff_roles;
    private $customer_id;
    private $customer_roles;
    private $company_id;
    private $company_name;
    private $company_url;
    private $company_location;

    public function __construct(Request $request)
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function generateLinks($linkid, $link_name, $link_route, $link_slug, $link_icon, $description, $parent_id)
    {
        if (Session::has('staff_id')) {
            $access = getAllAccess();

            $getChildren = RolePermission::whereIn('id', $access)->where(['status' => 'active', 'is_menu' => 'yes', 'parent' => $linkid, 'type' => 'staff'])->orderBy('order', 'ASC')->get();
        }

        if (count($getChildren) > 0) {
            foreach ($getChildren as $key => $value) {
                $linko[] = $this->generateLinks($value->id, $value->menu_name, $value->route, $value->slug, $value->icon, $value->description, $value->parent);
            }

            $sub_links = $linko;

            $links = [
                'id' => $linkid,
                'name' => $link_name,
                'icon' => $link_icon,
                'route' => $link_route,
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
                    'route' => $link_route,
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
                    'route' => $link_route,
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
        
        // Get permissions from staff roles
        if (Session::has('staff_id')) {
            $access = getAllAccess();
            $links = RolePermission::whereIn('id', $access)
            ->where(['status' => 'active', 'is_menu' => 'yes', 'parent' => NULL, 'type' => 'staff'])
            // ->where('company_id', sessionDetails('company_id'))
            ->orderBy('order', 'ASC')->get();
        }

        $lo = [];

        foreach ($links as $key => $value) {
            $lox[] = $value->link_name;
            $lo[] = $this->generateLinks($value->id, $value->menu_name, $value->route, $value->slug, $value->icon, $value->description, $value->parent);
        }

        return response()->json(['message' => 'Success', 'data' => $lo], 200);
    }

    public function singleLinkGenerator($parent_id)
    {
        $links = [];

        if (Session::has('staff_id')) {
            $access = getAllAccess();

            $links = RolePermission::whereIn('id', $access)->where(['status' => 'active', 'is_menu' => 'yes', 'id' => $parent_id, 'type' => 'staff'])->orderBy('order', 'ASC')->first();

            if (!$links) {
                return response()->json(['message' => 'Success', 'data' => []], 200);
            }
        }

        $lo = [];
        $lo = $this->generateLinks($links->id, $links->menu_name, $links->route, $links->slug, $links->icon, $links->description, $links->parent);

        return response()->json(['message' => 'Success', 'data' => $lo], 200);
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

        $data = $this->validate($request, [
            "name" => "required|string",
            "email" => "required|unique:users,email",
            "phone" => "required",
            "roles" => "sometimes",
            "status" => "required",
            "password" => "required"
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
        ]);

        $staff = Staff::create([
            'user_id' => $user->id,
            'status' => $data['status']
        ]);

        if ($request->has('roles')) {
            $staff->roles()->sync(array_values($data['roles']));
        }

        if ($this->settings->send_sms == 'yes') {
            $message = $this->getCreateAdminSms($user, $data['password']);
            $this->logMessages('sms', $user->phone, $message, $data['status'], null, auth()->user()->staff->id);
        }

        if ($this->settings->send_email == 'yes') {
            $subject = 'You are now an Staff';
            $message = $this->getCreateAdminEmail($user, $data['password']);
            $this->logMessages('email', $user->email, $message, $data['status'], $subject, auth()->user()->staff->id);
        }

        return back()->with('message', 'Created successfully');
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
        $permission = RolePermission::where('id', $request->permission_id)->first();

        $data['status'] = $request->status;
        $data['link_name'] = $request->link_name;
        $data['order'] = $request->order;
        $data['icon'] = $request->icon;
        $data['parent'] = $request->parent == 'none' ? 0 : $request->parent;
        
        $permission->update($data);

        return back()->with('message', 'Update succesful');
    }

    public function changeSpecialRequestStatus()
    {
        $permission = RolePermission::where('slug', 'special-requests')
        // ->where('company_id', sessionDetails('company_id'))
        ->first();

        if ($permission) {
            if ($permission->status == 'active') {
                $permission->update(['status' => 'inactive']);
            } else {
                $permission->update(['status' => 'active']);
            }
        } else {
            return response()->json(['error' => 'Resource not found', 'data' => null], 412);
        }

        return response()->json(['message' => 'Update successful', 'data' => null], 200);
    }

    public function destroy($id)
    {
    }

    public function adminAccessControls($access = null)
    {
        if (!empty($access)) {
            $packageAccess = $access;
        } else {
            $packageAccess = getPackageAccess($access);
        }
        
        $accessControls = RolePermission::with(['children' => function ($query) use ($packageAccess) {
            return $query->where(['status' => 'active', 'type' => 'staff'])->whereIn('id', $packageAccess)->orderBy('order', 'asc')->select('id', 'category', 'status', 'type', 'is_menu', 'menu_name', 'description', 'order', 'parent');
        }])->where(['status' => 'active', 'type' => 'staff'])
            ->orderBy('parent', 'asc')
            ->orderBy('order', 'asc')
            ->whereIn('id', $packageAccess)
            ->select('id', 'category', 'status', 'type', 'is_menu', 'menu_name', 'description', 'order', 'parent');

        $controls = clone $accessControls;
        $menus = $controls
            ->where('is_menu', 'yes')->whereNull('parent')
            ->get();

        $data = [
            'menus' => $menus,
            'others' => $accessControls->where('is_menu', 'no')->get(),
        ];

        return response()->json(['message' => 'Retrieved successfully', 'data' => $data], 200);
    }

    public function getPermissionToResource(Request $request)
    {

        $data = $this->validate($request, [
            'route_name' => 'required',
        ]);
        
        $name = $data['route_name'];

        if (isset($name)) {
            $permCheck = permissionsCheck($name);

            if (isset($permCheck)) {
                if (isset($permCheck['permission']) && $permCheck['permission'] == true) {
                    return response()->json($permCheck, $permCheck['code'] ?? 200);
                } else {
                    return response()->json(['message' => $permCheck['permission'], 'data' => []], 200);
                }
            } else {
                return response()->json(["message" => false, 'data' => 'Something went wrong while trying to validate access to resource!'], 200);
            }
        } else {
            return response()->json(["permission" => false, "error" => "Not Permissioned"], 200);
        }
    }
}
