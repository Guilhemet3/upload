<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;

class PostController extends Controller
{
    public function index(){
        $posts = Post::all();
        return view('index', compact('posts'));
    }

    public function store(Request $request){
        //para salvar arquivo   //local da pasta
        $path = $request->file('arquivo')->store('imagens', 'public');

        $post = new Post();
        $post->email = $request->input('email');
        $post->mensagem = $request->input('mensagem');
        //vamos salvar o path da onde esse arquivo esta armazendo
        $post->arquivo = $path; 
        $post->save();

        return redirect()->route('home');
    }

    public function destroy($id){
        $post = Post::find($id);
        if(isset($post)){
            $arquivo = $post->arquivo;
            Storage::disk('public')->delete('file.jpg');
            $post->delete();
        }
        return redirect()->route('home');
    }

    public function download($id){
        $post = Post::find($id);
        if(isset($post)){
            $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($post->arquivo);
            return response()->download($path);
        }
        return redirect()->route('home');
    }
}
