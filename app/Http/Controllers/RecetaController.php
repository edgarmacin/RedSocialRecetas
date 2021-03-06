<?php

namespace App\Http\Controllers;

use App\CategoriaReceta;
use App\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class RecetaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // auth()->user()->recetas->dd();
        $recetas = Auth::user()->recetas;

        return view('recetas.index')->with('recetas', $recetas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //DB::table('categoria_receta')->get()->pluck('nombre', 'id')->dd();
        //Obtener las categorias sin modelo
        //$categorias = DB::table('categoria_recetas')->get()->pluck('nombre', 'id');

        //Obtener con modelo
        $categorias = CategoriaReceta::all(['id', 'nombre']);

        return view('recetas.create')->with('categorias', $categorias);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd(public_path());
        //dd( $request['imagen']->store('upload-recetas', 'public') );
        // vaidar
        $data = $request->validate([
            'titulo'       => 'required | min:6',
            'categoria'    => 'required',
            'preparacion'  => 'required',
            'ingredientes' => 'required',
            'imagen'       => 'required | image',
        ]);
        // obtener ruta de la imagen
        $ruta_imagen = $request['imagen']->store('upload-recetas', 'public');

        // resize de la imagen
        $img = Image::make( public_path("storage/{$ruta_imagen}"))->fit(1000, 550);
        $img->save();

        // guardar en la bd sin modelo
        // DB::table('recetas')->insert([
        //     'titulo'       => $data['titulo'],
        //     'preparacion'  => $data['preparacion'],
        //     'ingredientes' => $data['ingredientes'],
        //     'imagen'       => $ruta_imagen,
        //     'user_id'      => Auth::user()->id,
        //     'categoria_id' => $data['categoria'],
        // ]);
        //dd( $request->all() );


        //almacenar en la BD con modelo

        auth()->user()->recetas()->create([
            'titulo'       => $data['titulo'],
             'preparacion'  => $data['preparacion'],
             'ingredientes' => $data['ingredientes'],
             'imagen'       => $ruta_imagen,
             'categoria_id' => $data['categoria'],
        ]);

        return redirect()->action('RecetaController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function show(Receta $receta)
    {
        return view('recetas.show', compact('receta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function edit(Receta $receta)
    {
        $categorias = CategoriaReceta::all(['id', 'nombre']);

        return view('recetas.edit', compact('categorias', 'receta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receta $receta)
    {
        // Revisar el policy (el policy que se crea lo tenemos que ligar al modelo con php artisan make:policy RecetaPolicy -m Receta)
        $this->authorize('update', $receta);

        $data = $request->validate([
            'titulo'       => 'required | min:6',
            'categoria'    => 'required',
            'preparacion'  => 'required',
            'ingredientes' => 'required',
        ]);

        // asignar los valores
        $receta->titulo = $data['titulo'];
        $receta->preparacion = $data['preparacion'];
        $receta->ingredientes = $data['ingredientes'];
        $receta->categoria_id = $data['categoria'];

        //si el usuario sube nueva img
        if(request('imagen')) {
            // obtener ruta de la imagen
            $ruta_imagen = $request['imagen']->store('upload-recetas', 'public');

            // resize de la imagen
            $img = Image::make( public_path("storage/{$ruta_imagen}"))->fit(1000, 550);
            $img->save();

            //asignar al objeto
            $receta->imagen = $ruta_imagen;
        }
        $receta->save();

        return redirect()->action('RecetaController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receta $receta)
    {
        // Revisar el policy (el policy que se crea lo tenemos que ligar al modelo con php artisan make:policy RecetaPolicy -m Receta)
        $this->authorize('delete', $receta);

        //eliminar la receta
        $receta->delete();

        return redirect()->action('RecetaController@index');
    }
}
