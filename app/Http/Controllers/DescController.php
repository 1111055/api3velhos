<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Desc;
use App\Http\Requests\DescRequest;


class DescController extends Controller
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
        //
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
    public function store(DescRequest $request)
    {
       


        $request->persist();

        return redirect()->route('pagina.edit', ['id' => request()->idpage])->with('sucess','Guardado com sucesso.');
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
    public function edit($id,$idpage)
    {
        
       $desc = Desc::find($id);

       return view('backend.Desc.edit' , compact('desc','idpage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

       // dd($request->titulo);
        $desc = Desc::find($id);


        $desc->titulo=$request->titulo;
        $desc->descricao1=$request->descricao;

        
        $desc->save();



     
       return redirect()->route('pagina.edit', ['id' => request()->idpage])->with('sucess','Guardado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$idpage)
    {
        Desc::destroy($id);

         return redirect()->route('pagina.edit', ['id' => $idpage])->with('sucess','Removido com sucesso.');
    }
}
