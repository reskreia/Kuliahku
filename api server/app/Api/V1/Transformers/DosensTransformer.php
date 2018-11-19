<?php


namespace App\Api\V1\Transformers;

use App\Dosen;
use League\Fractal\TransformerAbstract;

class DosensTransformer extends TransformerAbstract {

    public function transform(Dosen $user)
    {
        return [
            'id'        => $user->id,
            'name'        => $user->name
        ];
    }
}