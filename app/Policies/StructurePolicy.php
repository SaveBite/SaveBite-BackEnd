<?php

namespace App\Policies;

use App\Models\Manager;
use App\Models\Structure;
use Illuminate\Auth\Access\Response;

class StructurePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Manager $manager): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Manager $manager, Structure $structure): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Manager $manager): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Manager $manager, Structure $structure): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Manager $manager, Structure $structure): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Manager $manager, Structure $structure): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Manager $manager, Structure $structure): bool
    {
        //
    }
}
