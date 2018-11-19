<?php


namespace App\Api\V1\Transformers;

use App\Mimin;
use League\Fractal\TransformerAbstract;

class MiminsTransformer extends TransformerAbstract {

    public function transform(Mimin $user)
    {
        return [
            'id'        => $user->id,
            'name'        => $user->name
        ];
    }
}