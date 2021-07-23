<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pagina;
use App\Desc;
use App\Http\Requests\PaginaRequest;
use Image;
use File;

class PaginaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['getPage']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

         $pagina = Pagina::
                 orderBy('ordem','asc')->get();


        return view('backend.Pagina.index', compact('pagina'));
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
    
    public function getPage($id)
    {

        $page = Pagina::find($id);
         

         //dd($page->pagina);
         $pagelink = 'frontend.'.$page->link;

         if($page->link == ''){
                return redirect('/');
         }
         //dd($pagelink);
        return view($pagelink, compact('page'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaginaRequest $request)
    {
          $request->persist();

        return redirect()->route('pagina')->with('sucess','Criado com sucesso.');
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
       $pagina = Pagina::find($id);

       $desc =  Desc::where('page_id','=',$id)->get();

       


        return view('backend.Pagina.edit', compact('pagina','desc'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PaginaRequest $request, $id)
    {
        $pagina = Pagina::findOrFail($id);


        $_path = $pagina->pathimg;

      //  dd($request->file('banerimg'));

           if($request->hasFile('banerimg')) {
                      
                   //  dd("entra");
            
                        $photo = $request->file('banerimg');
                       
                        $filenamewithextension = $request->file('banerimg')->getClientOriginalName();
                 
                        //Nome Sem Extensão 
                        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                 
                        //Extenção do ficheiro
                        $extension = $request->file('banerimg')->getClientOriginalExtension();
                 
                        //Novo nome do ficheiro
                        $imagename = "banner_home.".$photo->getClientOriginalExtension(); 

                        $data = getimagesize($photo);
                        $width = $data[0];
                        $height = $data[1];

                        $namepng = "banner_home.png";
                        $namejgp = "banner_home.jpg";
                        $namegif = "banner_home.gif";
                        $nametiff = "banner_home.tiff";

                 
                        if(file_exists(base_path('/images/'.$namepng))){

                              unlink(base_path('/images/'.$namepng));

                        }
                       if(file_exists(base_path('/images/'.$namejgp))){

                              unlink(base_path('/images/'.$namejgp));

                        }
                        if(file_exists(base_path('/images/'.$namegif))){

                              unlink(base_path('/images/'.$namegif));

                        }
                        if(file_exists(base_path('/images/'.$nametiff))){

                              unlink(base_path('/images/'.$nametiff));

                        }

    
                        //Upload File                     
                        $file = $request->file('banerimg')->storeAs('images', $imagename, 'upload');
                        
                        
                       // crop image

                        $destinationPath = base_path('/images/CROP');
                        $thumb_img = Image::make($photo->getRealPath());
                    
                        if(file_exists(base_path('/images/CROP/'.$imagename))){

                              unlink(base_path('/images/CROP/'.$imagename));

                        }

                        $altura =   $height;
                        $comprimento = $width;

                        $divisaoalt = 1079 / $altura; 
                        $divisaocom = 1920 / $comprimento;

                        if($divisaoalt < $divisaocom){
                            $altfinal = $altura * $divisaoalt;
                            $cmpfinal = $comprimento * $divisaoalt;
                        }else{
                            $altfinal = $altura * $divisaocom;
                            $cmpfinal = $comprimento * $divisaocom;

                        }
                        $_path = $request->root().'/images/CROP/'.$imagename;
                        // Resized image
                        $thumb_img->resize($cmpfinal, $altfinal, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        // Canvas image
                        $canvas = Image::canvas(1920, 1079);
                        $canvas->insert($thumb_img, 'center');
                        $canvas->save($destinationPath.'/'.$imagename,90);
                                    
           }



        $input = $request->all();


        $pagina->nome      = $request->nome;
        $pagina->titulo      = $request->titulo;
        $pagina->subtitulo   = $request->subtitulo;
        $pagina->descricao   = $request->descricao;
        $pagina->descricao1   = $request->descricao1;
        $pagina->descricao2   = $request->descricao2;
        $pagina->link        = $request->link;
        $pagina->pathimg     = $_path;
        $pagina->ordem       = $request->ordem;
        $pagina->activo      = $request->activo;

        $pagina->save();

       
         return redirect()->route('pagina.edit', compact('pagina'))->with('sucess','Guardado com sucesso.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pagina::destroy($id);

         return redirect()->route('pagina')->with('sucess','Removido com sucesso.');
    }
}
