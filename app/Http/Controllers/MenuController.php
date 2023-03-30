<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller {

    /**
     * Display a listing of videos
     *
     * @return Response
     */
    public function index()
    {
        $menu = json_decode(Menu::orderBy('order', 'ASC')->get()->toJson());

        return view('menu.index', compact('menu'));
    }

    public function store(Request $request){
        $last_menu_item = Menu::orderBy('order', 'DESC')->first();

        if(isset($last_menu_item->order)){
            $new_menu_order = intval($last_menu_item->order) + 1;
        } else {
            $new_menu_order = 1;
        }
        $input['order'] = $new_menu_order;
        $input['name'] = $request->name;
        $input['url'] = $request->url;
        $input['type'] = $request->type;
        $menu= Menu::create($input);
        if(isset($menu->id)){
            return redirect('dashboard/menu')->with(['note' => 'Successfully Added New Menu Item', 'note_type' => 'success']);
        }
    }

    public function edit(Menu $menu){
        return view('menu.edit', compact('menu'));
    }


    public function destroy(Menu $menu){
        $child_menu_items = Menu::where('parent_id', $menu->id)->update(['parent_id' => null]);
        $menu->delete();
        return redirect('dashboard/menu')->with(['note' => 'Successfully Deleted Menu Item', 'note_type' => 'success']);
    }

    public function update(Menu $menu, Request $request){
        $menu->update($request->all());
        if(isset($menu)){
            return redirect('dashboard/menu')->with(['note' => 'Successfully Updated Category', 'note_type' => 'success']);
        }
    }

    public function order(Request $request)
    {
        $menu_item_order = json_decode($request->order);
        $post_categories = Menu::all();
        $order = 1;

        foreach ($menu_item_order as $menu_level_1){
            $level1 = Menu::find($menu_level_1->id);
            if ($level1->id) {
                $level1->order = $order;
                $level1->parent_id = NULL;
                $level1->save();
                $order += 1;
            }
            if (isset($menu_level_1->children)) {
                $children_level_1 = $menu_level_1->children;
                foreach ($children_level_1 as $menu_level_2) {
                    $level2 = Menu::find($menu_level_2->id);
                    if ($level2->id) {
                        $level2->order = $order;
                        $level2->parent_id = $level1->id;
                        $level2->save();
                        $order += 1;
                    }
                    if (isset($menu_level_2->children)) {
                        $children_level_2 = $menu_level_2->children;
                        foreach ($children_level_2 as $menu_level_3) {
                            $level3 = Menu::find($menu_level_3->id);
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