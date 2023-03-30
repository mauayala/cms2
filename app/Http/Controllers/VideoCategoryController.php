<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VideoCategory;

class VideoCategoryController extends Controller {
    
    public function index(){
        $video_categories = json_decode(VideoCategory::orderBy('order', 'ASC')->get()->toJson());

        return view('videos.categories.index', compact('video_categories'));
    }

    public function store(Request $request){
        $last_category = VideoCategory::orderBy('order', 'DESC')->first();

        if(isset($last_category->order)){
            $new_category_order = intval($last_category->order) + 1;
        } else {
            $new_category_order = 1;
        }
        $request->order = $new_category_order;
        $video_category = VideoCategory::create($request->all());
        if(isset($video_category->id)){
            return redirect('dashboard/videos/categories')->with(['note' => 'Categoria agregada con exito.', 'note_type' => 'success']);
        }
    }

    public function update(VideoCategory $category, Request $request){
        $category->update($request->all());
        return redirect('dashboard/videos/categories')->with(['note' => 'Categoria actualiada con exito.', 'note_type' => 'success']);
    }

    public function destroy(VideoCategory $category){
        VideoCategory::where('parent_id', $category->id)->update(['parent_id' => null]);
        $category->delete();
        return redirect('dashboard/videos/categories')->with(['note' => 'Categoria eliminada con exito.', 'note_type' => 'success']);
    }

    public function edit(VideoCategory $category){
        return view('videos.categories.edit', compact('category'));
    }

    public function order(Request $request){
        $category_order = json_decode($request->order);
        $video_categories = VideoCategory::all();
        $order = 1;
        
        foreach($category_order as $category_level_1) {
            $level1 = VideoCategory::find($category_level_1->id);
            if ($level1->id) {
                $level1->order = $order;
                $level1->parent_id = NULL;
                $level1->save();
                $order += 1;
            }

            if (isset($category_level_1->children)) {
                $children_level_1 = $category_level_1->children;
                foreach ($children_level_1 as $category_level_2) {
                    $level2 = VideoCategory::find($category_level_2->id);
                    if ($level2->id) {
                        $level2->order = $order;
                        $level2->parent_id = $level1->id;
                        $level2->save();
                        $order += 1;
                    }

                    if (isset($category_level_2->children)) {
                        $children_level_2 = $category_level_2->children;
                        foreach ($children_level_2 as $category_level_3) {
                            $level3 = VideoCategory::find($category_level_3->id);
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
