<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\LeaveAndAbsenceManagement;

class LeaveDateService
{
    /**
     * Calculate the leave end date and resumption date.
     *
     * @param string $startDate The start date of the leave in 'Y-m-d' format.
     * @param int $leaveDays The number of leave days.
     * @param bool $considerWeekends Whether to consider weekends as non-working days.
     * @param array $publicHolidays List of public holidays in 'Y-m-d' format.
     * @return array End date and resumption date.
     */
    public function calculateLeaveDates($startDate, $leaveDays, $considerWeekends = true, $publicHolidays = [])
    {
        $startDate = Carbon::parse($startDate);

        // Convert public holidays to Carbon objects if they are not already
        $publicHolidays = array_map(function ($holiday) {
            return Carbon::parse($holiday);
        }, $publicHolidays);

        // Adjust public holidays if they fall on weekends
        $adjustedPublicHolidays = $this->adjustHolidaysForWeekends($publicHolidays);

        $currentDate = $startDate->copy();
        $daysRemaining = $leaveDays - 1; // Start counting from the first day after $startDate

        while ($daysRemaining > 0) {
            // Move to the next day
            $currentDate->addDay();

            // Check if the current date is a working day based on $considerWeekends flag
            if ($this->isWorkingDay($currentDate, $considerWeekends, $adjustedPublicHolidays)) {
                $daysRemaining--;
            }
        }

        $endDate = $currentDate->toDateString(); // $currentDate is now the end date

        // Calculate resumption date starting from the end date
        $resumptionDate = $currentDate->copy()->addDay();

        // Move resumption date if it falls on a weekend or public holiday
        while (!$this->isWorkingDay($resumptionDate, $considerWeekends, $adjustedPublicHolidays)) {
            $resumptionDate->addDay();
        }

        return [
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate,
            'resumption_date' => $resumptionDate->toDateString()
        ];
    }

    /**
     * Check if a date is a working day.
     *
     * @param Carbon $date The date to check.
     * @param bool $considerWeekends Whether to consider weekends as non-working days.
     * @param array $publicHolidays List of public holidays.
     * @return bool
     */
    private function isWorkingDay($date, $considerWeekends, $publicHolidays)
    {
        if ($considerWeekends && $date->isWeekend()) {
            return false;
        }

        foreach ($publicHolidays as $holiday) {
            if ($date->isSameDay($holiday)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Adjust holidays if they fall on weekends.
     *
     * @param array $publicHolidays List of public holidays.
     * @return array Adjusted list of public holidays.
     */
    private function adjustHolidaysForWeekends($publicHolidays)
    {
        $adjustedHolidays = [];

        foreach ($publicHolidays as $holiday) {
            $adjustedHoliday = $holiday->copy();

            // Move holiday to the next weekday if it falls on a weekend
            while ($adjustedHoliday->isWeekend()) {
                $adjustedHoliday->addDay();
            }

            $adjustedHolidays[] = $adjustedHoliday;
        }

        return $adjustedHolidays;
    }


    /**
     * Check if a staff's leave application overlaps with any existing leave periods.
     *
     * @param int $staffId The ID of the staff applying for leave.
     * @param string $proposedStartDate The proposed start date of the new leave in 'Y-m-d' format.
     * @param string $proposedEndDate The proposed end date of the new leave in 'Y-m-d' format.
     * @return bool True if there is an overlap, false otherwise.
     */
    public function hasLeaveOverlap($staff_id, $proposedStartDate, $proposedEndDate)
    {
        // Convert proposed dates to Carbon objects for comparison
        $startDate = Carbon::parse($proposedStartDate);
        $endDate = Carbon::parse($proposedEndDate);

        // Query existing leave applications for the staff within the proposed period
        $existingLeave = LeaveAndAbsenceManagement::where('staff_id', $staff_id)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($query) use ($startDate, $endDate) {
                          $query->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                      });
            })
            ->exists();

        return $existingLeave;
    }

    public function getEmployeesOnLeaveToday()
    {
        $today = Carbon::today();

        return $this->getEmployeesOnLeaveBetweenDates($today, $today);
    }

    public function getEmployeesOnLeaveThisWeek()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        return $this->getEmployeesOnLeaveBetweenDates($startOfWeek, $endOfWeek);
    }

    public function getEmployeesOnLeaveThisMonth()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return $this->getEmployeesOnLeaveBetweenDates($startOfMonth, $endOfMonth);
    }

    public function getEmployeesOnLeaveThisYear()
    {
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();

        return $this->getEmployeesOnLeaveBetweenDates($startOfYear, $endOfYear);
    }

    protected function getEmployeesOnLeaveBetweenDates($startDate, $endDate)
    {
        $leaves = LeaveAndAbsenceManagement::with('staff.user')
                    ->whereDate('start_date', '<=', $endDate)
                    ->whereDate('end_date', '>=', $startDate)
                    ->get();

        $employeesOnLeave = $leaves->map(function ($leave) {
            return $leave->staff->user;
        });

        return $employeesOnLeave->unique('id')->values();
    }
}
