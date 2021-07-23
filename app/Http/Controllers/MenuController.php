<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menu;
use App\Http\Requests\MenuRequest;

class MenuController extends Controller
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


           $menus = Menu::
                 where('submenu', '=', '')
                 ->orWhereNull('submenu')
                 ->orderBy('ordem','asc')->get();
        return view('backend.Menu.index', compact('menus'));
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
    public function store(MenuRequest $request)
    {
             //  $user = Auth::user();
    
        $request->persist();

        return redirect()->route('menu')->with('sucess','Criado com sucesso.');
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
     
       $menu = Menu::find($id);


       $menus = Menu::
                 where("submenu","=", $id) 
                 ->orderBy('ordem','asc')
                 ->get();

       $menustmp = Menu::
                 orderBy('ordem','asc')->get();


       $selmenu = $menustmp->pluck('menu','id');

      // dd($selmenu);
       $selmenu->prepend('-- Escolha um Submenu -- ',0);

       return view('backend.Menu.edit' , compact('menu','menus','selmenu'));
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

        $menu = Menu::find($id);
        $menu->menu=$request->menu;
        $menu->link=$request->link;
        $menu->submenu=$request->submenu;
        $menu->descricao=$request->descricao;
        $menu->ordem=$request->ordem;
        $menu->path=$request->path;

        $menu->save();



     
        return redirect()->route('menu')->with('sucess','Actualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         Menu::destroy($id);

         return redirect()->route('menu')->with('sucess','Criado com sucesso.');
    }
}
