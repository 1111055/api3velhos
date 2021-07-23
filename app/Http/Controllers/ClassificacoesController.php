<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classificacao;
use App\Usergrupo;
use App\Jogo;
use App\Aposta;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClassificacoesController extends Controller
{
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

    public function getall(Request $request)
    {
       
       $now = Carbon::now();
     
       $mes = 7;

       $ano = $now->year;


        $teste2tmp = DB::select("select res.user_id,us.name ,SUM(case when result=1 then 1 else 0 end) as totalacerto,COUNT(*) AS totalapos,ROUND((SUM(case when result=1 then 1 else 0 END) /COUNT(*))*100,2) AS media FROM resutladosestatisticas res JOIN users us ON us.id = res.user_id WHERE YEAR(res.created_at)='".$ano."'and MONTH(res.created_at)>='".$mes."' and DAY(res.created_at)>=1 GROUP BY res.user_id,us.name ORDER BY SUM(case when result=1 then 1 else 0 END) desc,  ROUND((SUM(case when result=1 then 1 else 0 END) /COUNT(*))*100,2) desc");


       return response()->json($teste2tmp);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }
}
