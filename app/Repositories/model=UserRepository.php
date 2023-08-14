<?php

namespace App\Repositories;

use App\Models\model=User;
use App\Repositories\BaseRepository;

class model=UserRepository extends BaseRepository
{
    protected $fieldSearchable = [
        
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return model=User::class;
    }
}
