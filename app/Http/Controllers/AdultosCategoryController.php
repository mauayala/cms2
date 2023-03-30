<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdultosCategory;

class AdultosCategoryController extends Controller {

    public function index(){
        $adultos_categories = AdultosCategory::orderBy('order', 'ASC')->get();

        return view('adultos.categories.index', compact('adultos_categories'));
    }

    public function store(Request $request){
        $last_category = AdultosCategory::orderBy('order', 'DESC')->first();

        $data = $request->all();
        $data['order'] = intval($last_category->order) + 1;
        $adult_categories = AdultosCategory::create($data);
        return redirect('dashboard/adultos/categories')->with(['note' => 'Categoria agregada con exito.', 'note_type' => 'success']);
    }

    public function update(AdultosCategory $category, Request $request){
        $category->update($request->all());
        return redirect('dashboard/adultos/categories')->with(['note' => 'Categoria actualizada con exito.', 'note_type' => 'success']);
    }

    public function destroy(AdultosCategory $category){
        AdultosCategory::where('parent_id', $category->id)->update(['parent_id' => null]);
        $category->delete();

        return true;
    }

    public function edit(AdultosCategory $category){
        return view('adultos.categories.edit', ['category' => $category]);
    }

    public function order(Request $request){
        $category_order = json_decode($request->order);
        $adult_categories = AdultosCategory::all();
        $order = 1;
        
        foreach($category_order as $category_level_1) {
            $level1 = AdultosCategory::find($category_level_1->id);
            if ($level1->id) {
                $level1->order = $order;
                $level1->parent_id = NULL;
                $level1->save();
                $order += 1;
            }

            if (isset($category_level_1->children)) {
                $children_level_1 = $category_level_1->children;
                foreach ($children_level_1 as $category_level_2) {
                    $level2 = AdultosCategory::find($category_level_2->id);
                    if ($level2->id) {
                        $level2->order = $order;
                        $level2->parent_id = $level1->id;
                        $level2->save();
                        $order += 1;
                    }

                    if (isset($category_level_2->children)) {
                        $children_level_2 = $category_level_2->children;
                        foreach ($children_level_2 as $category_level_3) {
                            $level3 = AdultosCategory::find($category_level_3->id);
                            if ($level3->id) {
                                $level3->order = $order;
                                $level3->parent_id = $level2->id;
                                $level3->save();
                                $order += 1;
                            }
                        }
                    }
                }
            }
        }
        return 1;
    }
}
