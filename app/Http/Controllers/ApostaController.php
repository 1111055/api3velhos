<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aposta;
use App\Jogo;
use App\Http\Requests\ApostaRequest;
use Carbon\Carbon;


class ApostaController extends Controller
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $bet = Aposta::where('user_id','=',request()->user_id)->where('jogo_id','=',request()->jogo_id)->get();

        $pode = $this->canBetData(request()->jogo_id);


        if($pode == true){
            if(count($bet) == 0){
                    Aposta::create([
                        'user_id'    => request()->user_id,
                        'jogo_id'    => request()->jogo_id,
                        'aposta'     => request()->aposta
                        
                    ]);
            }else{
                $value = $bet->first();

                 $value->aposta  = request()->aposta;
                 $value->save();
            }               


             return response()->json(0);
        }else{
             return response()->json(5);
        }
    }


    private function canBetData($id)
    {
       

        $jogo = Jogo::find($id);

        $data = $jogo->data_encontro;
        $dataconvert = Carbon::parse($data);

        // Data do jogo
        $yearjogo   = $dataconvert->year;
        $monthjogo  = $dataconvert->month;
        $dayjogo    = $dataconvert->day;   

        $gamehour   = $jogo->hora;


        $splitName = explode(' ', $gamehour); 

        $splitNametwo = explode(':', $splitName[0]); 

        $horajogo =  $splitNametwo[0];
        $minutojogo =  $splitNametwo[1];

        if($splitName[1] == "PM"){

            $horajogotmp = $this->convert12to24($splitNametwo[0]);
          
        }

        $time =Carbon::now()->setTimezone('Europe/Lisbon');

        // dados now
        $year   = $time->year;
        $month  = $time->month;
        $day    = $time->day;        
        $hora   = $time->hour;
        $minuto = $time->minute;

        $horajogo = $jogo->hora;
        $splitName = explode(' ', $horajogo); 

        $splitNametwo = explode(':', $splitName[0]); 

        $horajogo =  $splitNametwo[0];
        $minutosjogo =  $splitNametwo[1];

        if($splitName[1] == "PM"){

            $horajogo = $this->convert12to24($splitNametwo[0]);
        }
         if($splitName[1] == "AM" &&  $horajogo == 12){

                 $horajogo = 00;
         }
        

        if($year < $yearjogo){

               return true;

        }elseif($year == $yearjogo){
            if($month < $monthjogo){
                return true;
            }elseif($month == $monthjogo){
                  if($day < $dayjogo){
                        return true;
                    }elseif($day == $dayjogo){

                         if($hora < $horajogo){
                                return true;
                           }elseif($hora == $horajogo){
                              if($minuto < $minutojogo){
                              
                                  return true;
                              }else{
                                return false;
                              }

                            }else{
                              return false;
                            }

                    }else{
                        return false;
                    }

            }else{
                return false;
            }

        }else{
            return false;
        }

    

        return $result;


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
