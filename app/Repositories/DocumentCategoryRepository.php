<?php

namespace App\Repositories;

use App\Models\DocumentCategory;
use App\Repositories\Interfaces\DocumentCategoryRepositoryInterface;

class DocumentCategoryRepository implements DocumentCategoryRepositoryInterface
{
    protected $model;

    public function __construct(DocumentCategory $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function paginate($perPage)
    {
        return $this->model->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $documentCategory = $this->find($id);
        return $documentCategory->update($data);
    }

    public function delete($id)
    {
        $documentCategory = $this->find($id);
        return $documentCategory->delete();
    }
}
