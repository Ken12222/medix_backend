<?php

namespace App\Policies;

use App\Models\PatientReport;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PatientReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PatientReport $patientReport): bool
    {
        return $user->id === $patientReport->doctor_id || $user->id === $patientReport->patient_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PatientReport $patientReport): bool
    {
        return $user->id === $patientReport->doctor_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PatientReport $patientReport): bool
    {
        return $user->id === $patientReport->doctor_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PatientReport $patientReport)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PatientReport $patientReport)
    {
        //
    }
}
