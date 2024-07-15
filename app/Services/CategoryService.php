<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getAllCategories()
    {
        return Category::query()->get();
    }

    public function createCategory($data)
    {
        return Category::query()->create($data);
    }

    public function getCategoryById($id)
    {
        return Category::query()->findOrFail($id);
    }

    public function updateCategory($id, $data)
    {
        $category = Category::query()->findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function deleteCategory($id)
    {
        $category = Category::query()->findOrFail($id);
        $category->delete();
        return $category;
    }
}
