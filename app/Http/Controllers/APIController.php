<?php

namespace App\Http\Controllers;

use App\Models\CurrentAPI;
use Illuminate\Http\Request;

use App\Models\HistoryAPI;

use Validator;

class APIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        

        $api = currentAPI::latest()->paginate(5);
        $data = HistoryAPI::orderBy('updated_at', 'desc')->get();

        return view("api.index",compact('api'),['historylist'=>$data])
            ->with('i', (request()->input('page',1) - 1) * 5);
    }

    public function shows()
    {
        $data = HistoryAPI::all();
        return view('api.index',['historylist'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('api.update');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        Validator::extend('without_spaces', function($attr, $value, $request){
        return preg_match('/^\S*$/u', $value);
        });

       $id = request('idCurr');

        $request->validate([
            'newAPI' => 'required|without_spaces'],
            ['newAPI.required' => 'API Key is missing',
            'newAPI.without_spaces' => 'It should not contain blank spaces']);

        if ($id == 0) {
           currentAPI::create([
        'api_key' => request('newAPI'),
        'created_by' => request('loguser'),
        'updated_by' => request('loguser'),
        'id' => auth()->id()
        ]);

        return redirect()->route('api.index')
            ->with('success','API Updated Successfully');

       }else{
        currentAPI::create([
        'api_key' => request('newAPI'),
        'created_by' => request('loguser'),
        'updated_by' => request('loguser'),
        'id' => auth()->id()
        ]);

        

        $this->shiftdata($id);

        return redirect()->route('api.index')
            ->with('success','API Updated Successfully');
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CurrentAPI  $currentAPI
     * @return \Illuminate\Http\Response
     */
    public function edit(CurrentAPI $currentAPI)
    {
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CurrentAPI  $currentAPI
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
       $request->validate([
            'newAPI' => 'required']);

        currentAPI::create([
        'api_key' => request('newAPI'),
        'created_by' => request('loguser'),
        'updated_by' => request('loguser'),
        'id' => auth()->id()
        ]);

        return redirect()->route('api.index')
            ->with('success','API Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CurrentAPI  $currentAPI
     * @return \Illuminate\Http\Response
     */
    public function destroy(CurrentAPI $currentAPI, $id)
    {       
        $currentAPI->delete();
        return redirect()->route('api.index')
            ->with('success','API Updated Successfully');
    }

    public function shiftdata($id){


        $idGET = currentAPI::find($id);

            HistoryAPI::create([
            'api_key' => $idGET->api_key,
            'created_by' => $idGET->created_by,
            'created_at' => $idGET->created_by,
            'updated_by' => $idGET->updated_by,
            'id' =>  $idGET->id
            ]);

        $this->destroyUponInsert($id);
         
    }

    public function destroyUponInsert($id){

        $currAPI = currentAPI::find($id);
        $currAPI->delete();
        
    }

    
}
