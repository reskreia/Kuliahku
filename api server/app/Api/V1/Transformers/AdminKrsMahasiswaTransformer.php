<?php


namespace App\Api\V1\Transformers;

use App\Models\KrsData;
use League\Fractal\TransformerAbstract;

class AdminKrsMahasiswaTransformer extends TransformerAbstract {

    public function transform(KrsData $data)
    {
        return [
            'id'        => $user->id,
            'name'        => $user->name
        ];
    }
}