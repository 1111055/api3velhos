<?php

namespace App\Http\Controllers;

use App\Expressoes;
use Illuminate\Http\Request;
use App\Http\Requests\ExpressoesRequest;

class ExpressoesController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exp = Expressoes::
                 orderBy('id','asc')->get();


        return view('backend.Expressoes.index', compact('exp'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpressoesRequest $request)
    {
        $request->persist();

        return redirect()->route('expressoes')->with('sucess','Criado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $exp = Expressoes::find($id);
         return view('backend.Expressoes.edit', compact('exp'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpressoesRequest $request, $id)
    {

        $exp = Expressoes::findOrFail($id);


      // dd($request->activo);
        $exp->expression  = $request->expression;
        $exp->descricao   = $request->desc;

        $exp->save();

       
         return redirect()->route('expressoes.edit', compact('exp'))->with('sucess','Guardado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         Expressoes::destroy($id);

         return redirect()->route('expressoes')->with('sucess','Removido com sucesso.');
    }
}
