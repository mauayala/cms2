<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recommendation;

class RecommendationController extends Controller {

    public function index(){
        $recommendations = json_decode(Recommendation::orderBy('order', 'ASC')->get()->toJson());
        $recommendations_raw = Recommendation::has('video')->orHas('serie')->orderBy('order', 'ASC')->get();
        
        return view('recommendations.index', compact('recommendations', 'recommendations_raw'));
    }

    public function store(Request $request){
        $last_recommendation = Recommendation::orderBy('order', 'DESC')->first();

        if(isset($last_recommendation->order)){
            $new_recommendation_order = intval($last_recommendation->order) + 1;
        } else {
            $new_recommendation_order = 1;
        }
        $recommendation = Recommendation::create([
            'video_id' => $request->video_id,
            'serie_id' => $request->serie_id,
            'order' => $new_recommendation_order
        ]);
        
        return redirect('dashboard/recommendations')->with(['note' => 'Recommendations agregada con exito.', 'note_type' => 'success']);
    }

    public function update(Recommendation $recommendation, Request $request){
        $recommendation->update($request->all());
        
        return redirect('dashboard/recommendations')->with(['note' => 'Recommendations actualiada con exito.', 'note_type' => 'success']);
    }

    public function destroy(Recommendation $recommendation){
        $recommendation->delete();
        return redirect('dashboard/recommendations')->with(['note' => 'Recommendations eliminada con exito.', 'note_type' => 'success']);
    }

    public function edit(Recommendation $recommendation){
        return view('recommendations.edit', compact('recommendation'));
    }

    public function order(Request $request){
        $category_order = json_decode($request->order);
        
        $recommendations = Recommendation::all();
        if(count($category_order) > count($recommendations)){
            return 2;
        }
        $order = 1;
        
        foreach($category_order as $category_level_1) {
            $level1 = Recommendation::find($category_level_1->id);
            if ($level1->id) {
                $level1->order = $order;
                $level1->save();
                $order += 1;
            } else {
                return 0;
            }
        }
        return 1;
    }
}
