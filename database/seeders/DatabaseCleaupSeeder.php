<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DatabaseCleaupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::statement("TRUNCATE contract_payments;TRUNCATE contract_allocations;TRUNCATE contract_payment_threads;TRUNCATE notifications;TRUNCATE message_transactions;TRUNCATE admin_logs;TRUNCATE transactions;TRUNCATE staff_disengagements;TRUNCATE customer_contracts;TRUNCATE leave_and_absence_management");

        $password = '$2y$10$HhbgECfuD8NISCi5WdmZrupVJcZZ5gfniSVD5LL1AuHhqjX6D6q1u';
        DB::statement("INSERT INTO users (id, title, country_id, state_id, lga_id, status, type, first_name, middle_name, last_name, phone_number, residential_address, date_of_birth, user_permissions, company_id, branch_id, gender, email, username, avatar, staff_id, email_verified_at, password, next_of_kin, job_information, remember_token, created_at, updated_at, customerId, alternate_number, alternate_email, marital_status) VALUES
        (1, NULL, NULL, NULL, NULL, 'active', 'staff', 'New STFF', NULL, 'Staff', '09090909', NULL, '2022-01-12 00:00:00', '4,1,2', 1, 0, 'male', 'davedeloper@gmail.com', 'Accel Admin', 'uploads/realestateapp-a6af6/avatars/2024/03/05/1165e799c904e00.jpeg', '0001', NULL, '{$password}', '{\"nok_first_name\":\"new name\",\"nok_last_name\":\"new last\",\"nok_phone_number\":\"new phone\",\"nok_house_address\":\"new address\",\"nok_email_address\":\"new email\",\"nok_relationship\":\"new relatship\"}', NULL, NULL, '2023-05-24 22:05:21', '2024-03-06 16:10:56', NULL, NULL, NULL, NULL)");

        DB::statement("INSERT INTO staff (id, user_id, company_id, branch_id, created_at, updated_at, department_id, jobrole_id, jobtype_id, commission_id, bank_id, date_of_employment, pfa_id, account_name, account_number, sort_code, swift_code, nhf_number) VALUES
        (1, 1, 1, 1, '2023-05-24 22:05:21', '2024-03-09 21:40:48', 3, NULL, NULL, NULL, 10001, '2022-12-12 05:00:00', 10003, NULL, NULL, NULL, NULL, NULL)");
    }
}
