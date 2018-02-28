<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($user)
    {
        $data = $user->toArray();
        switch (true) {
            case $user->role_id == User::ROLE_ADMIN:
                $data['role_name'] = 'Admin';
                break;
            default:
                $data['role_name'] = 'Normal';
                break;
        }
        return $data;
    }
}
