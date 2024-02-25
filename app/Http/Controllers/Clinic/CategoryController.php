<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Clinic\CategoryCreateRequest;
use App\Http\Requests\Clinic\CategoryUpdateRequest;
use App\Http\Resources\Clinics\CategoryResource;
use App\Http\Services\Clinic\ClinicService;
use App\Models\Clinic\Category;
use App\Traits\ResponseMessage;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ResponseMessage;

    public  function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function index()
    {
            return CategoryResource::collection(Category::all());
    }

    public function show(Category $category)
    {
            if (is_null($category)) {
                return $this->SendMessage("category is incorrect or Not Exisit.", 404);
            }
            return new CategoryResource($category);
    }

    public function store(CategoryCreateRequest $request) {

            $created_category = ClinicService::CreateCategory(
                $request->name,
            );
            $data = new CategoryResource($created_category);
            return $this->SendResponse("Category Created.", $data, 201);

    }

    public function update(CategoryUpdateRequest $request, Category $category) {

            // Check if the category exists
            if (is_null($category)) {
                return $this->SendMessage("category is incorrect or Not Exisit.", 404);
            }

            $Updated_category = ClinicService::UpdateCategory( $category ,$request);
            $data = new CategoryResource($Updated_category);

            return $this->SendResponse("Category Updated.", $data, 200);

    }

    public function destroy(Category $category)
    {

            ClinicService::DeleteCategory($category);
            return $this->SendMessage("Category Deleted.", 200);

    }
}
