<?php


namespace App\Api\V1\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UsersTransformer extends TransformerAbstract {

    public function transform(User $user)
    {
        return [
            'id'        => $user->id,
            'nim'       => $user->nim,
            'name'      => $user->name
        ];
    }
}