<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use App\Category;
use Session;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	// Create a variable and store all the blog posts in it from the database
	    $posts = Post::orderBy('id', 'desc')->paginate(5);

	    // Return a view and pass in the above variable
	    return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $categories = Category::all();
      return view('posts.create')->withCategories($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // validate the data
	    $this->validate($request, array(
	    	'title' => 'required|max:225',
		    'slug' => 'required|alpha_dash|min:5|max:225|unique:posts,slug',
		    'category_id' => 'required|integer',
		    'body' => 'required'
	    ));

	    // store in the database
	    $post = new Post;

	    $post->title = $request->title;
	    $post->slug = $request->slug;
	    $post->category_id = $request->category_id;
	    $post->body = $request->body;

	    $request->session()->flash("success", 'The blog post was successfully save!');

	    $post->save();

	    // redirect to another page
	    return redirect()->route('posts.show', $post->id);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    	$post = Post::find($id);
    	return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      // Find the post in the database and save as a var
      $post = Post::find($id);
      $categories = Category::all();

      $cats = array();
      foreach ($categories as $category) {
        $cats[$category->id] = $category->name;
      }
      // Return the view and pass in the var we previously created
      return view('posts.edit')->withPost($post)->withCategories($cats);
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
      // Validate the data
      $post = Post::find($id);

      if ($request->input('slug') == $post->slug) {
        $this->validate($request, array(
          'title' => 'required|max:225',
          'category_id' =>'required|integer',
          'body' => 'required'
        ));
      } else {
        $this->validate($request, array(
          'title' => 'required|max:225',
          'slug' => 'required|alpha_dash|min:5|max:225|unique:posts,slug',
          'category_id' =>'required|integer',
          'body' => 'required'
        ));
      }

      // Save the data to the database
      $post = Post::find($id);

      $post->title = $request->input('title');
      $post->slug = $request->input('slug');
      $post->category_id = $request->input('category_id');
      $post->body = $request->input('body');

      $post->save();

      // Set flash data with success massage
      Session::flash('success', 'This post was successfully saved.');

      // Redirect with flash data to posts.show
      return redirect()->route('posts.show', $post->id);
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

      $post->delete();

      Session::flash('success', 'The post was successfully deleted.');
      return redirect()->route('posts.index');
    }
}
