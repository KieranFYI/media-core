<?php

namespace KieranFYI\Media\Core\Policies;

use Illuminate\Database\Eloquent\Model;
use KieranFYI\Roles\Core\Policies\AbstractPolicy;

class MediaPolicy extends AbstractPolicy
{
    /**
     * Determine whether the user can view the model.
     *
     * @param mixed $user
     * @param Model $model
     * @return bool
     */
    public function view(mixed $user, Model $model): bool
    {
        return $this->viewAny($user, $model);
    }
}