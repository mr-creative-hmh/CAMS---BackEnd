<?php

namespace App\Http\Services\Clinic;

use App\Models\Clinic\Category;
use App\Models\Clinic\Clinic;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException; // Import QueryException for database errors

class ClinicService
{
    public static function CreateCategory($name)
    {
        $created_category = Category::Create([
            'name' => $name,
        ]);

        return $created_category;
    }

    public static function GetCategoryById($id)
    {
        return Category::indOrFail($id);
    }

    public static function UpdateCategory(Category $cat , Request $request) {

        try {
            $catdata = $request->validated();

            // Attempt to update the category
            $cat->update($catdata);

            // Return the updated category
            return $cat;
        } catch (QueryException $e) {
            // Handle database query exceptions, e.g., unique constraint violations
            throw new \Exception("Category update failed: " . $e->getMessage());
        } catch (\Exception $e) {
            // Handle other exceptions
            throw new \Exception("Category update failed: " . $e->getMessage());
        }

    }

    public static function DeleteCategory(Category $cat)
    {
        $cat->delete();
    }

    public static function CreateClinic($name , $address , $phone , $operating_hours , $category_id )
    {
        $created_clinic = Clinic::Create([
            'name' => $name,
            'address' => $address,
            'phone' => $phone,
            'operating_hours' => $operating_hours,
            'category_id' => $category_id,
        ]);

        return $created_clinic;
    }


    public static function GetClinicById($id)
    {
        return Clinic::indOrFail($id);
    }


    public static function UpdateClinic(Clinic $clinic, Request $request) {

        $clinicdata = $request->validated();
        $clinic->update($clinicdata);
        return $clinic;

    }

    public static function DeleteClinic(Clinic $clinic)
    {
        $clinic->delete();
    }

}
