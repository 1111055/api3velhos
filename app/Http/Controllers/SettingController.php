<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use App\Http\Requests\SettingRequest;
use Image;
use File;


class SettingController extends Controller
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

         $setting = Setting::first();
                


        return view('backend.Setting.index', compact('setting'));
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
    public function update(SettingRequest $request, $id)
    {
        $setting = Setting::findOrFail($id);



        $input = $request->all();

        $setting->fill($input)->save();



        if($request->hasFile('profile_image')) {
			
			
				    	$photo = $request->file('profile_image');

                        $data = getimagesize($photo);
                        $width = $data[0];
                        $height = $data[1];
						
                        //Nome Do Ficheiro
                        $filenamewithextension = $request->file('profile_image')->getClientOriginalName();
                 
                        //Nome Sem Extensão 
                        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                 
                        //Extenção do ficheiro
                        $extension = $request->file('profile_image')->getClientOriginalExtension();
                 
                        //Novo nome do ficheiro
						$imagename = "logotipo".'.'.$photo->getClientOriginalExtension(); 
                 
					    if(file_exists(base_path('/logotipo/IMG/logotipo.png'))){

							  unlink(base_path('/logotipo/IMG/logotipo.png'));

						}
	

                        //Upload File					  
					    $file = $request->file('profile_image')->storeAs('IMG', $imagename, 'upload');
                        
                        
                       // crop image

						$destinationPath = base_path('/logotipo/CROP');
						$thumb_img = Image::make($photo->getRealPath());
					
					    if(file_exists(base_path('/logotipo/CROP/logotipo.png'))){

							  unlink(base_path('/logotipo/CROP/logotipo.png'));

						}
						// Resized image

                        $altura =   $height;
                        $comprimento = $width;

                        $divisaocom = 1000 / $comprimento;
                        $divisaoalt = 1000 / $altura; 

                        if($divisaoalt < $divisaocom){
                            $altfinal = $altura * $divisaoalt;
                            $cmpfinal = $comprimento * $divisaoalt;
                        }else{
                            $altfinal = $altura * $divisaocom;
                            $cmpfinal = $comprimento * $divisaocom;

                        }

                        $thumb_img->resize($cmpfinal, $altfinal, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        // Canvas image
                        $canvas = Image::canvas(1000, 1000);
                        $canvas->insert($thumb_img, 'center');
                        $canvas->save($destinationPath.'/'.$imagename,100);
            										

           return redirect()->route('setting')->with(['sucess' => "Guardado com sucesso."]);
      }   else{
        return redirect()->route('setting')->with(['sucess' => "Guardado com sucesso."]);
      }
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
