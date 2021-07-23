<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;
use App\Http\Resources\Article as ArticleResource;
use Image;
use File;
use App\Categoriablog;
use App\Categoria;
use App\Review;



class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $art = Article::where('title','!=',' ')->where('activo','=','1')->orderBy('created_at','desc')->paginate(5);

      //  dd($art);

        return ArticleResource::collection($art);

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response->
     */
    public function list()
    {
       $art = Article::where('title','!=',' ')->
                 orderBy('created_at','desc')->get();


        return view('backend.Articles.index', compact('art'));
    }


    public function search(Request $request)
    {

       $string = $request['search'];
       $art    = ' ';
       $value = ' ';

       if($string  !== ' '){

             $art = Article::where('title', 'LIKE', '%'.$string.'%')->orWhere('body', 'LIKE', '%'.$string.'%')->Where('activo', '=', '1')->orderBy('created_at','desc')->get();       
             $value = 'Pesquisa: '.$string;
       }

    

       $cat2 = Categoriablog::getListsort();
         return view('frontend.search', compact('art','cat2','value'));

    }
      public function categoria($id)
    {

       $cat    = $id;
       $art    = ' ';
       $value = ' ';

       if($cat !== ' '){
              $art    = Article::where('category', '=', $cat)->Where('activo', '=', '1')->orderBy('created_at','desc')->get();  
              $cattmp = Categoriablog::find($id);
              $value  = 'Categoria: '. $cattmp->titulo;
       }

       $cat2 = Categoriablog::getListsort();
         return view('frontend.search', compact('art','cat2','value'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       //dd("entrou");
        $article = $request->isMethod('put') ? Article::findOrFail($request->article_id) : new Article;

         $article->title = $request->input('title');
       //  $art->body  = $request->input('body');


       $selcat = Categoriablog::getCatBlog();

       $campains = Newslettermailchimp::getLists();


       $campains = $campains['lists'];

         if($article->save()){
             return view('backend.Articles.edit', compact('article','selcat','campains'));
            //return new ArticleResource($art);
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         
       $art     = Article::findorFail($id);

       $artlist = Article::where('activo','=',1)->orderBy('title','desc')->get();
       $chave = 0;

       foreach ($artlist as $key => $value) {
         // dump($value->id);
           if($value->id==$id){
                    $chave = $key;
           }
       }

     //  dd($art->getreviews);

       $artlist =  $artlist->toArray();
       if($chave == 0){
         $chave++;
    
         $anterior = 0;
         $seguinte = $artlist[$chave]['id'];
       }else{
         $chaveseguinte = $chave + 1 ;
         $chaveanterior = $chave - 1;
    
         $anterior = $artlist[$chaveanterior]['id'];
         $seguinte = isset($artlist[$chaveseguinte]) ? $artlist[$chaveseguinte]['id']  : 0;
       }


       $cat = Categoriablog::getListsort();

       $art2 = Article::where('activo','=','1')->orderBy('created_at','desc')->paginate(5)->toArray();


       // return new ArticleResource($art);
       return view('frontend.detail', compact('art','art2', 'cat', 'seguinte', 'anterior'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $article = Article::find($id);

       $selcat = Categoriablog::getCatBlog();

       $campains = Newslettermailchimp::getLists();

       $campains = $campains['lists'];
       // $campains = "";
    // dd($campains);
        return view('backend.Articles.edit', compact('article','selcat','campains'));
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
        $article = Article::findOrFail($id);


        $_path = $article->path;

          //  dd($request->file('banerimg'));

           if($request->hasFile('banerimg')) {
                      
            //        dd("entra");
            
                        $photo = $request->file('banerimg');
                       
                        $filenamewithextension = $request->file('banerimg')->getClientOriginalName();
                 
                        //Nome Sem Extensão 
                        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                 
                        //Extenção do ficheiro
                        $extension = $request->file('banerimg')->getClientOriginalExtension();
                 
                        //Novo nome do ficheiro
                        $imagename = "article_".$id.".".$photo->getClientOriginalExtension(); 

                        $data = getimagesize($photo);
                        $width = $data[0];
                        $height = $data[1];

                 
                        if(file_exists(base_path('/images/Articles/'.$imagename))){

                              unlink(base_path('/images/Articles/'.$imagename));

                        }
                       

    
                        //Upload File                     
                        $file = $request->file('banerimg')->storeAs('images', $imagename, 'upload');
                        
                        
                       // crop image

                        $destinationPath = base_path('/images/Articles');
                        $thumb_img = Image::make($photo->getRealPath());
                    
                        if(file_exists(base_path('/images/Articles/'.$imagename))){

                              unlink(base_path('/images/Articles/'.$imagename));

                        }

                        $altura =   $height;
                        $comprimento = $width;

                        $divisaoalt = 515 / $altura; 
                        $divisaocom = 775 / $comprimento;

                        if($divisaoalt < $divisaocom){
                            $altfinal = $altura * $divisaoalt;
                            $cmpfinal = $comprimento * $divisaoalt;
                        }else{
                            $altfinal = $altura * $divisaocom;
                            $cmpfinal = $comprimento * $divisaocom;

                        }
                        $_path = $request->root().'/images/Articles/'.$imagename;
                        // Resized image
                        $thumb_img->resize($cmpfinal, $altfinal, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        // Canvas image
                        $canvas = Image::canvas(775, 515);
                        $canvas->insert($thumb_img, 'center');
                        $canvas->save($destinationPath.'/'.$imagename,90);
                                    
           }


        //dd($request);

        $article->title       = $request->title;
        $article->body        = $request->descricao;
        $article->category    = $request->category;
        $article->path        = $_path;
        $article->ordem       = $request->ordem;
        $article->activo      = $request->activo != null ? $request->activo : 0;
        $article->fonte       = $request->fonte;

        $article->save();

        if($request->emailcheck === 'on'){
          

             // dd("entrou2");
          $emailtmp = view('backend.Emails.emailchimp',compact('article'))->render();

         // dd($emailtmp);

          $retorno = Newslettermailchimp::createCampaign('3 Velhos',"3velhos@gmail.com", $article->title, $request->emailsend ,$emailtmp, $article->body, "3velhos.pt/login");

        // dd($retorno);
 
          Newslettermailchimp::sendCampaign($retorno['id']);
            
        }

       
         return redirect()->route('articles.edit', compact('article'))->with('sucess','Guardado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         
         Article::destroy($id);

         $art = Article::where('title','!=',' ')->
                 orderBy('created_at','desc')->get();

         return view('backend.Articles.index', compact('art'))->with('sucess','Removido com sucesso.');
         
    }
}
