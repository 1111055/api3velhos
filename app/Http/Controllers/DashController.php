<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Analytics\Period;
use App\Classificacao;
use App\Jogo;
use App\Aposta;
use Carbon\Carbon;
use App\Grupo;
use App\Usergrupo;

class DashController extends Controller
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
    public function index(Request $request)
    {
        $user = Auth::user();
          $filter = 0;

        $grupos = Usergrupo::where('user_id','=',$user->id)->get();
      
        $class = Classificacao::
                 orderBy('pontos','desc')->get();
        $datasession =  $request->session()->get('datafilter');

        if(!$datasession){

                $datatmp = carbon::now()->format('M d');;
                $jogo = Jogo::where('data_encontro', '>=', carbon::now()->startOfDay())->where('data_encontro', '<', carbon::now()->addDays(1)->startOfDay())->
                orderBy('hora','asc')->get();
                $request->session()->put('datafilter', Carbon::now());

                  $datain =  Carbon::now();

                $daybefore =  $datain->subDay()->format('Y-m-d');
                $dayafter =  $datain->addDay()->format('Y-m-d');
                   
        }else{

                $datatmp = $datasession->format('M d');
              
                $antes = $datasession->startOfDay();
                $depois = $antes->copy()->addDays(1)->startOfDay()->format('y-m-d H:i:s');

                $jogo = Jogo::where('data_encontro', '>=',   $antes->startOfDay()->format('y-m-d H:i:s'))->where('data_encontro', '<', $depois)->
                       orderBy('hora','asc')->get();


               $datain =  Carbon::parse($datatmp);
              //  dd( $datain );

                $daybefore =  Carbon::parse($datatmp)->subDay()->format('Y-m-d');
                $dayafter =   Carbon::parse($datatmp)->addDay()->format('Y-m-d');
              
        }


        $userId = Auth::id();

        $ck2 = 0; $ck3 = 0;$ck1 = 0;$ck0 = 0;
 
        if(!$request->op1 && !$request->op2 && !$request->op3 && !$request->op0){
            // $jogo =  $jogo->where('situacao','=','0');
              $ck0 = 1; 
              $request->session()->put('opfilter', 0);
        }else{
            if($request->op0){
                $ck2 = 0; $ck3 = 0;$ck1 = 0;$ck0 = 1;
                 $request->session()->put('opfilter', 0);
                  $filter = 1;
            }

            if($request->op1){

                $jogo = $jogo->where('situacao','=','0');
                $ck2 = 0; $ck3 = 0;$ck1 = 1;$ck0 = 0;
                 $request->session()->put('opfilter', 1);
                   $filter = 1;
            }

            if($request->op2){

                $jogo = $jogo->where('situacao','=','1');
                $ck2 = 1; $ck3 = 0;$ck1 = 0;$ck0 = 0;
                 $request->session()->put('opfilter', 2);
                   $filter = 1;
            }

            if($request->op3){

                 $jogo = $jogo->where('cancelado','=','1');
                 $ck2 = 0; $ck3 = 1;$ck1 = 0;$ck0 = 0;
                  $request->session()->put('opfilter', 3);
                    $filter = 1;
            }
        }



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

                 if($value->resultado != "0"){

                         if($exist->aposta == $value->resultado){
                                   $value['classaposta'] = "success";
                                   $value['vencedor']    =  "<span class='label label-default'>".$result."</span> <i class='fa fa-thumbs-up'></i>";

                         }else{

                                   $value['classaposta'] = "danger";
                                   $value['vencedor']    =  "<span class='label label-default'>".$result."</span><i class='fa fa-thumbs-down'></i>";
                         }
                 
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

         if($splitName[1] == "AM" &&  $horajogo == 12){

           

               
                $horajogotmp = "00";
                $teste = str_replace($horajogo,$horajogotmp, $value->hora);
                $testetmp = str_replace("AM",' ', $teste);

         }

        //  dd($horajogo);


            $value->hora = $testetmp;

           // dd($horajogotmp);
                   
         }    
         $jogo = $jogo->sortBy('hora');
      
         // dd($jogo);
        
        $user->authorizeRoles(['master', 'supermaster','Guest']);

      
        return view('backend.index', compact('class','userId','jogo','ck1','ck2','ck3','ck0','datatmp','grupos','daybefore','dayafter','filter'));
    }




    /**
     * Show the form for creating a new resource.
     *s
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function filter(Request $request)
    {
        $user = Auth::user();
        $grupos = Usergrupo::where('user_id','=',$user->id)->get();
        
        $datasession =  $request->session()->get('datafilter');       
        $option =  $request->session()->get('opfilter'); 
        $data = $request->data;

        $class = Classificacao::
                 orderBy('pontos','desc')->get();


        $jogo = Jogo::
                 orderBy('data_encontro','desc')->get();

                 $userId = Auth::id();

        $ck2 = 0; $ck3 = 0;$ck1 = 0;$ck0 = 0;


        if($option == 0){
            $ck2 = 0; $ck3 = 0;$ck1 = 0;$ck0 = 1;
        }

        if($option == 1){

            $jogo = $jogo->where('situacao','=','0');
            $ck2 = 0; $ck3 = 0;$ck1 = 1;$ck0 = 0;
        }

        if($option == 2){

            $jogo = $jogo->where('situacao','=','1');
            $ck2 = 1; $ck3 = 0;$ck1 = 0;$ck0 = 0;
        }

        if($option == 3){

             $jogo = $jogo->where('cancelado','=','1');
             $ck2 = 0; $ck3 = 1;$ck1 = 0;$ck0 = 0;
        }
        


        if($data){

               $datain =  Carbon::parse($data);
               $datain2 =  Carbon::parse($data);

               $subnov = $datain->startOfDay();
               $jogo = $jogo->where('data_encontro', '=', $subnov);

                $daybefore =   Carbon::parse($data)->subDay()->format('Y-m-d');
                $dayafter  =   $datain2->addDay()->format('Y-m-d');

                $datatmp = $subnov->format('M d');
                $request->session()->put('datafilter',   $subnov);

          /*   if($data == 1){
                $subnov = $datasession->subDays(1)->startOfDay();

                $jogo = $jogo->where('data_encontro', '=', $subnov);

                  $datatmp =  $subnov->format('M d');
                   $request->session()->put('datafilter',   $subnov);

             }else{
                 $subnov = $datasession->addDays(1)->startOfDay();
                  $jogo = $jogo->where('data_encontro', '=', $subnov);
                  $datatmp = $subnov->format('M d');
                   $request->session()->put('datafilter',   $subnov);
             }*/

        }


        foreach ($jogo as $key => $value) {

            $exist = Aposta::where('user_id','=',$userId)->where('jogo_id', '=', $value->id)->first();

             $value['classaposta'] = "default";

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

                if($value->resultado != "0"){
                             if($exist->aposta == $value->resultado){
                                       $value['classaposta'] = "success";
                                       $value['vencedor']    =  "<span class='label label-default'>".$result."</span> <i class='fa fa-thumbs-up'></i>";

                             }else{
                                       $value['classaposta'] = "danger";
                                       $value['vencedor']    =  "<span class='label label-default'>".$result."</span><i class='fa fa-thumbs-down'></i>";
                          }
                }
            
            }else{
                  $value['_aposta'] = "0";
                   $value['classaposta'] = "default";
            }

            if($value->situacao == 0){

                 $value['vencedor']    =  "<span class='label label-default'>Aberto</span><i class='fa fa-smile'></i>";

             }
               if($value->cancelado == 1){

                 $value['vencedor']    =  "<span class='label label-default'>Cencelado</span><i class='fa fa-frown-o'></i>";

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

       if($splitName[1] == "AM" &&  $horajogo == 12){

               
                $horajogotmp = "00";
                $teste = str_replace($horajogo,$horajogotmp, $value->hora);
                $testetmp = str_replace("AM",' ', $teste);
         }
            $value->hora = $testetmp;
                   
         }    
     
        $jogo = $jogo->sortBy('hora');

        $user->authorizeRoles(['master', 'supermaster','Guest']);
         $filter = 1;


        return view('backend.index', compact('class','userId','jogo','ck1','ck2','ck3','ck0','datatmp','grupos','daybefore','dayafter','filter'));

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
            $conversao = 12;
              break;
          default:
              $hora;
        }

        return $conversao;


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

    public function piechart($id,$tipo)
    {
      
        return response()
            ->json(0);
    }







}
