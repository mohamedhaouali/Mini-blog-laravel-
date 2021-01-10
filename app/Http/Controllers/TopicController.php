<?php

namespace App\Http\Controllers;

use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class TopicController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topics = Topic::latest()->paginate(10);
        return view('topics.index', compact('topics'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('topics.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validation du formulaire
        $topic = $data = $request->validate([
            'title' => 'required|min:5',
            'content' => 'required|min:10',

        ]);
        // il cree le topic si utilisateur connecte
        $topic = auth()->user()->topics()->create( $data);
        return redirect()->route('topics.show', $topic->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function show(Topic $topic)
    {
        return view('topics.show', compact('topic'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function edit(Topic $topic)
    {
        // on ajoute cette ligne pour la protection des topics
        $this->authorize('update', $topic);
        return view('topics.edit', compact('topic'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Topic $topic)
    {
        // on ajoute cette ligne pour la protection des topics
        $this->authorize('update', $topic);
        // validation du formulaire
        $data = $request->validate([
            'title' => 'required|min:5',
            'content' => 'required|min:10',

        ]);

        $topic->update($data);

        return redirect()->route('topics.show', $topic->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Topic $topic)
    {
        // on ajoute cette ligne pour la protection des topics
        $this->authorize('delete', $topic);
        Topic::destroy($topic->id);
        return redirect('/');
    }

    public function showFromNotification(Topic $topic, DatabaseNotification $notification)
    {
        $notification->markAsRead();

        return view('topics.show', compact('topic'));
    }
}