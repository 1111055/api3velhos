<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\Http\Requests\RoleRequest;
use App\RuleUser;

class RoleController extends Controller
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

        $roles = Role::
                 orderBy('name','asc')->get();


        return view('backend.Role.index', compact('roles'));
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
    public function store(RoleRequest $request)
    {
        $request->persist();

        return redirect()->route('role')->with('sucess','Criado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $role =  Role::find($id);
         //dd($role->users);

         foreach ($role->users  as $key => $valuetmp) {

                  if($valuetmp->path != null){
                    $jpgimg = public_path('/logotipo/User/user_'.$valuetmp->id.'.jpg');
                    $pngimg = public_path('/logotipo/User/user_'.$valuetmp->id.'.png');
                    if (!file_exists($jpgimg) & !file_exists($pngimg)) {
                       
                             $valuetmp->path = request()->root().'/img/Produtos/CROP/noimage.png';
                     }
                  }else{
                             $valuetmp->path = request()->root().'/img/Produtos/CROP/noimage.png';
                  }
        
        }
 
        return view('backend.Role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $role = Role::find($id);

         return view('backend.Role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $ids
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         Role::destroy($id);

         return redirect()->route('role')->with('sucess','Removido com sucesso.');
    }

    public function removerole($role,$user)
    {
        
        $rrr = RuleUser::where('role_id','=',$role)->where('user_id','=',$user)->first();
      //  dd( $rrr );
        if($rrr !== null){
            RuleUser::destroy($rrr->id);
        }

        $role = Role::find($role);

        foreach ($role->users  as $key => $valuetmp) {

                  if($valuetmp->path != null){
                    $jpgimg = public_path('/logotipo/User/user_'.$valuetmp->id.'.jpg');
                    $pngimg = public_path('/logotipo/User/user_'.$valuetmp->id.'.png');
                    if (!file_exists($jpgimg) & !file_exists($pngimg)) {
                       
                             $valuetmp->path = request()->root().'/img/Produtos/CROP/noimage.png';
                     }
                  }else{
                             $valuetmp->path = request()->root().'/img/Produtos/CROP/noimage.png';
                  }
        
        }

        return view('backend.Role.show', compact('role'));
    }
}
