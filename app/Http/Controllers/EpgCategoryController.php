<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EpgCategory;

class EpgCategoryController extends Controller {
    public function index(){
        $epg_categories = json_decode(EpgCategory::orderBy('order', 'ASC')->get()->toJson());

        return view('epg.categories.index', compact('epg_categories'));
    }

    public function store(Request $request){
        $last_category = EpgCategory::orderBy('order', 'DESC')->first();

        if(isset($last_category->order)){
            $new_category_order = intval($last_category->order) + 1;
        } else {
            $new_category_order = 1;
        }
        $request->order = $new_category_order;
        $epg_categories = EpgCategory::create($request->all());
        if(isset($epg_categories->id)){
            return redirect('dashboard/epg/categories')->with(['note' => 'Successfully Added Your New EPG Category', 'note_type' => 'success']);
        }
    }

    public function update(EpgCategory $category, Request $request){
        $category->update($request->all());
        if(isset($category)){
            return redirect('dashboard/epg/categories')->with(['note' => 'Successfully Updated Category', 'note_type' => 'success']);
        }
    }

    public function destroy(EpgCategory $category){
        EpgCategory::where('parent_id', $category->id)->update(['parent_id' => null]);
        $category->delete();
        return redirect('dashboard/epg/categories')->with(['note' => 'Successfully Deleted Category', 'note_type' => 'success']);
    }

    public function edit(EpgCategory $category){
        return view('epg.categories.edit', compact('category'));
    }

    public function order(Request $request){
        $category_order = json_decode($request->order);
        $epg_categories = EpgCategory::all();
        $order = 1;
        
        foreach($category_order as $category_level_1) {
            $level1 = EpgCategory::find($category_level_1->id);
            if ($level1->id) {
                $level1->order = $order;
                $level1->parent_id = NULL;
                $level1->save();
                $order += 1;
            }

            if (isset($category_level_1->children)) {
                $children_level_1 = $category_level_1->children;
                foreach ($children_level_1 as $category_level_2) {
                    $level2 = EpgCategory::find($category_level_2->id);
                    if ($level2->id) {
                        $level2->order = $order;
                        $level2->parent_id = $level1->id;
                        $level2->save();
                        $order += 1;
                    }

                    if (isset($category_level_2->children)) {
                        $children_level_2 = $category_level_2->children;
                        foreach ($children_level_2 as $category_level_3) {
                            $level3 = EpgCategory::find($category_level_3->id);
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
