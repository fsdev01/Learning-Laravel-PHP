<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Post; // Use Model - query db for data
//use DB; // traditional SQL query


class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Ensure user is authenticated
        // for index and show pages
        $this->middleware('auth',['index','show']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$posts = Post::all();
        //$posts = DB::select("SELECT * FROM posts"); // traditional SQL Query
       // $posts = Post::orderBy('title','desc')->get();
        //$posts = Post::orderBy("title","asc")->take(1)->get();
        //$posts = Post::orderBy('title',"asc")->paginate(10);
        $posts = Post::orderBy('created_at',"desc")->paginate(10);
        return view("posts.index")->with('posts',$posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("posts.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate Request Data (title and body fields)
        // cover_image (1) must be image type (e.g. jpeg, png) (2) optional provided 
        //(3) under 2mb due Apache default upload file size
        $this->validate($request,['title'=>'required','body'=>'required','cover_image'=>'image|nullable|max:1999']);

        // Handle File Upload
        if($request->hasFile('cover_image')){
            // Get Filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename (standard php function)
            $filename = pathinfo($filenameWithExt,PATHINFO_FILENAME);
            // Get just file extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename . "_" . time() . "." . $extension;
            // Upload Image to public/storage/cover_images folder
            $path = $request->file('cover_image')->storeAs('public/cover_images',$fileNameToStore);
        } 
        else { // User does not provide image. Use default image.
            $fileNameToStore = 'noimage.jpg';
        }


        // Create Post in DB (model)
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        // redirect to /posts with success session variable
        return redirect('/posts')->with('success','Post Created');
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
        $post = Post::find($id);
        return view('posts.show')->with('post',$post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        
        // Check that the current user is the owner of the post
        if(auth()->user()->id !== $post->user_id){
            return redirect("/posts")->with('error','Unauthorised Page');
        }


        return view("posts.edit")->with('post',$post);
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
        // Validate Request Data (title and body fields)
        $this->validate($request,['title'=>'required','body'=>'required']);

        // Find Post
        $post = Post::find($id);

        // Check that post exists
        if($post === NULL){
            return redirect("/posts")->with('error','Post does not exist');
        }

        // Handle File Upload
        if($request->hasFile('cover_image')){
            // Get Filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get just filename (standard php function)
            $filename = pathinfo($filenameWithExt,PATHINFO_FILENAME);
            // Get just file extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename . "_" . time() . "." . $extension;
            // Upload Image to public/storage/cover_images folder
            $path = $request->file('cover_image')->storeAs('public/cover_images',$fileNameToStore);
        } 

        // Check that the current user is the owner of the post
        if(auth()->user()->id !== $post->user_id){
            return redirect("/posts")->with('error','Unauthorised Page');
        }

        // Update Post Fields
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        // Only update imagefile if it is provided, otherwise leave it
        if($request->hasFile('cover_image')){
            // Delete old image
            Storage::delete("public/cover_images/" . $post->cover_image);
            // Set new image
            $post->cover_image = $fileNameToStore;


        }
        $post->save();

        // redirect to /posts with success session variable
        return redirect('/posts')->with('success','Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        
        // Check that post exists
        if($post === NULL){
            return redirect("/posts")->with('error','Post does not exist');
        }

        // Check that the current user is the owner of the post
        if(auth()->user()->id !== $post->user_id){
            return redirect("/posts")->with('error','Unauthorised Page');
        }

        // Delete old image
        if($post->cover_image != 'noimage.jpg'){
            // Delete old image
            Storage::delete("public/cover_images/" . $post->cover_image);
        }
        
        $post->delete();
        return redirect('/posts')->with('success','Post Removed');
    }
}
