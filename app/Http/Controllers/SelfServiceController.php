<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Staff;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SelfServiceController extends Controller
{
    public function myProfile()
    {
        $user = auth()->user();
        $data = [
            'profile_completeness' => '',
            'leave' => '',
            'absence' => '',
            'my_transactions' => '',
            'time_in_company' => '1 year, 8 months',
            'my_anniversary' => 'January 2nd',
            'open_deals' => '',
            'closed_deals' => '',

        ];

        dd($data);
    }

    public function dashboard()
    {
        $staffs = Staff::where('staff.company_id', $this->sessionDetails('company_id'))
            ->join('users', 'users.id', '=', 'staff.user_id')
            ->whereNotNull('users.date_of_birth')
            ->orderBy('users.date_of_birth', 'ASC')
            ->select('staff.id AS staff_id', 'users.id AS user_id', 'staff.created_at', 'user_id', 'users.date_of_birth AS date_of_birth', 'users.first_name', 'users.last_name', 'users.avatar', 'staff.company_id')
            ->take(10)
            ->get();

        $staffsX = Staff::where('staff.company_id', $this->sessionDetails('company_id'))
            ->join('users', 'users.id', '=', 'staff.user_id')
            ->whereNotNull('staff.date_of_employment')
            ->orderBy('users.date_of_birth', 'ASC')
            ->select('staff.id AS staff_id', 'users.id AS user_id', 'user_id', 'staff.date_of_employment AS date_of_employment', 'users.first_name', 'users.last_name', 'users.avatar', 'staff.company_id')
            ->take(10)
            ->get();


        $col = Staff::where('staff.company_id', $this->sessionDetails('company_id'))
            ->join('users', 'users.id', '=', 'staff.user_id')
            ->orderBy('staff.created_at', 'ASC')
            ->select('staff.id AS staff_id', 'users.id AS user_id', 'staff.created_at', 'user_id', 'users.first_name', 'users.last_name', 'users.avatar', 'staff.company_id')
            ->take(10)
            ->get();

        $additional_details = !empty(Auth::user()->additional_details) ? json_decode(Auth::user()->additional_details, true) : [];
        $nok_details = !empty(Auth::user()->next_of_kin) ? json_decode(Auth::user()->next_of_kin, true) : [];

        $expectedProfile = [
            'first_name' => !empty(Auth::user()->first_name) ? 1 : 0,
            'middle_name' => !empty(Auth::user()->middle_name) ? 1 : 0,
            'last_name' => !empty(Auth::user()->last_name) ? 1 : 0,
            'email_address' => !empty(Auth::user()->email) ? 1 : 0,
            'phone_number' => !empty(Auth::user()->phone) ? 1 : 0,
            'alternate_email_address' => !empty($additional_details['alternate_email']) ? 1 : 0,
            'username' => !empty(Auth::user()->username) ? 1 : 0,
            'gender' => !empty(Auth::user()->gender) ? 1 : 0,
            'date_of_birth' => !empty(Auth::user()->date_of_birth) ? 1 : 0,
            'marital_status' => !empty($additional_details['marital_status']) ? 1 : 0,
            'alternate_number' => !empty($additional_details['alternate_number']) ? 1 : 0,
            'nationality' => !empty($additional_details['nationality']) ? 1 : 0,
            'state' => !empty($additional_details['state']) ? 1 : 0,
            'lga' => !empty($additional_details['lga']) ? 1 : 0,
            'residential_address' => !empty($additional_details['residential_address']) ? 1 : 0,
            'nok_first_name' => !empty($nok_details['nok_first_name']) ? 1 : 0,
            'nok_last_name' => !empty($nok_details['nok_last_name']) ? 1 : 0,
            'nok_phone_number' => !empty($nok_details['nok_phone_number']) ? 1 : 0,
            'nok_house_address' => !empty($nok_details['nok_house_address']) ? 1 : 0,
            'nok_email_address' => !empty($nok_details['nok_email_address']) ? 1 : 0,
            'nok_relationship' => !empty($nok_details['nok_relationship']) ? 1 : 0
        ];

        $profile_completenes = array_sum($expectedProfile) / count($expectedProfile) * 100;
      
        // $payments = Payment::where('staff_id', Auth::user()->staff->id)->get();
        // dd($payments);
        $birthdays = [];
        $work_anniversary = [];
        $colleagues = [];

        foreach ($staffs as $b) {
            $birthdays[] = [
                'id' => $b->user_id,
                'first_name' => $b->first_name,
                'last_name' => $b->last_name,
                'avatar' => !empty($b->avatar) ? url('/') . $b->avatar : '',
                'date_of_birth' => Carbon::parse($b->date_of_birth)->format('jS \\of F'),
            ];
        }

        foreach ($staffsX as $b) {
            $work_anniversary[] = [
                'id' => $b->staff_id,
                'first_name' => $b->first_name,
                'last_name' => $b->last_name,
                'avatar' => !empty($b->avatar) ? url('/') . $b->avatar : '',
                'date_of_employment' => Carbon::parse($b->date_of_employment)->format('jS \\of F'),
            ];
        }

        $time = Carbon::parse(Auth::user()->staff->date_of_employment)->diff(Carbon::now());
        $time_in_company = $time->y.($time->y > 1 ? 'years, ':'year, ').$time->m. ($time->m > 1 ? 'months' : 'month');
        $my_anniversary = Carbon::parse(Auth::user()->staff->date_of_employment)->format('jS\\, F');

        foreach ($col as $b) {
            $colleagues[] = [
                'id' => $b->staff_id,
                'first_name' => $b->first_name,
                'last_name' => $b->last_name,
                'avatar' => !empty($b->avatar) ? url('/') . $b->avatar : '',
                'created_at' => Carbon::parse($b->created_at)->format('jS \\of F'),
            ];
        }

        return response()->json(['birthdays' => $birthdays, 'work_anniversary' => $work_anniversary, 'colleagues' => $colleagues, 'time_in_company'=>$time_in_company,'profile_completeness'=> (int) $profile_completenes.'%','my_anniversary'=> $my_anniversary], 200);
    }
}
