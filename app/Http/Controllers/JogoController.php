<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jogo;
use App\User;
use App\Aposta;
use App\Classificacao;
use App\Article;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;




class JogoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $userId = 1;

        $jogo = Jogo::where('data_encontro', '>=', carbon::now()->startOfDay())->where('data_encontro', '<', carbon::now()->addDays(1)->startOfDay())->
        orderBy('hora','asc')->get();


        foreach ($jogo as $key => $value) {

            $exist = Aposta::where('user_id','=',$userId)->where('jogo_id', '=', $value->id)->first();

            

            if($exist != null){
               //  dd($exist);
                 $value['_aposta'] = $exist->aposta;

                  $result = "Empate";

                 if($value->resultado == 1){
                      $result = "Terminado, vencedor: ".$value->eq1;
                 }
                  if($value->resultado == 2){
                      $result = "Terminado, vencedor: ".$value->eq2;
                 }


                 if($exist->aposta == $value->resultado){
                           $value['classaposta'] = "success";
                           $value['vencedor']    =  "<span class='label label-default'>".$result."</span> <i class='fa fa-thumbs-up'></i>";

                 }else{

                           $value['classaposta'] = "danger";
                           $value['vencedor']    =  "<span class='label label-default'>".$result."</span><i class='fa fa-thumbs-down'></i>";
                 }

            
            }else{
                  $value['_aposta'] = "0";
                   $value['classaposta'] = "default";
            }

            if($value->situacao == 0){

                 $value['vencedor']    =  "<span class='label label-default'>Aberto</span><i class='fa fa-smile'></i>";
                  $value['classaposta'] = "default";

             }
               if($value->cancelado == 1){

                 $value['vencedor']    =  "<span class='label label-default'>Cencelado</span><i class='fa fa-frown-o'></i>";
                  $value['classaposta'] = "default";

             }
            
            $horajogo =  $value->hora;

            $splitName = explode(' ', $horajogo); 

            $splitNametwo = explode(':', $splitName[0]); 

            $horajogo =  $splitNametwo[0];
            $minutosjogo =  $splitNametwo[1];

            if($splitName[1] == "PM"){

                $horajogotmp = $this->convert12to24($splitNametwo[0]);
                $teste = str_replace($horajogo,$horajogotmp, $value->hora);
                $testetmp = str_replace("PM",' ', $teste);

            }else{

                $testetmp = str_replace("AM",' ', $value->hora);
            }


            $value->hora = $testetmp;

           // dd($horajogotmp);
                   
         }    
         $jogo = $jogo->sortBy('hora');
      
         
        
      

        return $jogo->toJson();

    }

      public function convert12to24($hora)
    {

        $conversao = $hora;



        switch ($hora) {
          case 1:
           $conversao = 13;
            break;
          case 2:
            $conversao = 14;
            break;
          case 3:
             $conversao = 15;
            break;
          case 4:
            $conversao = 16;
             break;
          case 5:
            $conversao = 17;
             break;
          case 6:
            $conversao = 18;
             break;
          case 7:
            $conversao = 19;
             break;
          case 8:
            $conversao = 20;
             break;
          case 9:
            $conversao = 21;
             break;
           case 10:
            $conversao = 22;
             break;
           case 11:
            $conversao = 23;
              break;
           case 12:
            $conversao = 24;
              break;
          default:
              $hora;
        }

        return $conversao;


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

    public function closebet()
    {
         $id = request()->jogoid;
         $resultado = request()->resultado;
       
         $bet = Jogo::find($id);


 if($bet->situacao == 0){
         $exist = Aposta::where('jogo_id', '=', $id)->get();

         if($resultado != 3 && $resultado != "Cancelado"){

             $bet->situacao = 1;
             $bet->resultado = $resultado;

             foreach ($exist as $key => $value) {

                if($value->aposta ==  $resultado){

                    $class = Classificacao::where('user_id', '=', $value->user_id)->first();
                    $class->pontos += 1;
                    $class->save();
                }

             }
         }else{
        
             $bet->situacao = 2;
             $bet->resultado = 3;
             $bet->cancelado = 1;
         }

         $bet->save();
       
       $now = Carbon::now();
      // $mes = $now->month;
         $mes = 7;
         $ano = $now->year;



         $vit = $bet->resultado == "1" ? "Jogo: ".$bet->eq1." x ".$bet->eq2." vitória do ".$bet->eq1 : "vitória do ".$bet->eq2;

         if($bet->resultado == "x"){
                 $vit = "O jogo ".$bet->eq1." x ".$bet->eq2." acabou empatado.";
         }
          $users_tmp = User::where('activo', '=','1')->pluck('ID')->all();
          $class = Classificacao::whereIn('user_id', $users_tmp)->orderBy('pontos','desc')->get();

          $totalap = Aposta::where('aposta', '=', $bet->resultado)->where('jogo_id', '=', $id)->get();

          $html = count($totalap)." Pessoas acertaram no jogo ". $bet->eq1." x ".$bet->eq2." neste resultado.<br/><br/>";

          
          $html2 = "Tabela Classificativa<br/>";

          $html1 =   "<table class='table'>
                      <tbody>";
                foreach ($class as $key => $value) {


                     // $teste = DB::table('resutladosestatisticas')->whereMonth('created_at', '=', $mes)->where('user_id','=', $value->utilizador[0]->id)->count();
                     // $teste2 = DB::table('resutladosestatisticas')->where('result', '=', 1)->whereMonth('created_at', '=', $mes)->where('user_id','=', $value->utilizador[0]->id)->count();


                      $teste = DB::table('resutladosestatisticas')->whereMonth('created_at', '>=', $mes)->whereYear('created_at', '>=', $ano)->where('user_id','=', $value->utilizador[0]->id)->count();
                      $teste2 = DB::table('resutladosestatisticas')->where('result', '=', 1)->whereMonth('created_at', '>=', $mes)->whereYear('created_at', '>=', $ano)->where('user_id','=', $value->utilizador[0]->id)->count();

                   


                      if($teste > 0){
                          $perc = ($teste2 * 100) / $teste;
                      }
                      else{
                           $perc = 0;
                      }

                     $teste_tmp2 = $teste2."/".$teste." (".(int)$perc."%)";



                       $html1 =  $html1."<tr>
                                  <td>".$value->utilizador[0]->name."</td><td>".   $teste_tmp2."</td>
                                </tr>";
                }
                       
            $html1 = $html1."</tbody></table>";

            $html =  $html.$html2.$html1;

            if($bet->cancelado == 1){$html = $bet->eq1." x ".$bet->eq2.". Este jogo foi cancelado!"; $vit = "Cancelado";}

           Article::create([
                'title'    => "Jogo Fechado, ". $vit,
                'body'     => $html, 
                'activo'   => 1,
                'fonte'    => " 3 Velhos."
                
            ]); 




         return response()->json(0);
     }else{
          return response()->json(1);
     }
    }

    public function newgame(Request $request)
    {


         $countjogos = Jogo::where('data_encontro', '=', request()->data_encontro)->where('cancelado', '=', 0)->get();

        //dd(count($countjogos));

         if(count($countjogos) == 8){




              return response()->json(1);
         }else{

                 Jogo::create([
                    'eq1'           => request()->eq1,
                    'eq2'           => request()->eq2,
                    'data_encontro' => request()->data_encontro,
                    'hora'          => request()->hora,
                    'situacao'      => 0,
                    'resultado'     => 0,
                ]);

              
           
                $countjogostmp = Jogo::where('data_encontro', '=', request()->data_encontro)->where('cancelado', '=', 0)->get();

                  if( count($countjogostmp) == 8){
                      $game = Jogo::where('cancelado','=','0')->orderBy('id', 'desc')->take(8)->get();


                      $html = "Estão inseridos novos jogos. Boa Sorte. <br/><br/>";


                      $html =  $html."<table class='table'>
                                  <tbody>";
                                    foreach ($game as $key => $value) {
                                           $html =  $html."<tr>
                                                      <td>".$value->eq1."</td><td>x</td><td>".$value->eq2."</td>
                                                    </tr>";
                                    }
                                   
                       $html = $html."</tbody></table>";

            
              
                       Article::create([
                            'title'    => "Novos Jogos Disponiveis",
                            'body'     => $html, 
                            'activo'   => 1,
                            'fonte'    => " 3 Velhos."
                            
                        ]); 
                  }

           return response()->json(0);
        }
    }
}
