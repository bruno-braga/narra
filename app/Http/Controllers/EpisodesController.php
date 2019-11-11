<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Repository\EpisodeRepositoryInterface;
use App\Repository\ProgramRepositoryInterface;

use App\Audio;

use App\Episode;
use App\Program;

use App\Http\Resources\EpisodeCollection;

class EpisodesController extends Controller
{
    private $episode;
    private $program;

    public function __construct(EpisodeRepositoryInterface $episode, ProgramRepositoryInterface $program)
    {
        $this->episode = $episode;
        $this->program = $program;

        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $episodes = $this
            ->episode
            ->getAll();

        $programs = $this
            ->program
            ->getProgramsWithEpisodes($episodes, Auth::id());

        return view(
            'episodes.index',
            [
                'programs' => $programs
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $episodes = $this
            ->episode
            ->getAll();

        $programs = $this
            ->program
            ->getProgramsWithEpisodes($episodes, Auth::id());

        return view(
            'episodes.create',
            [
                'programs' => $programs
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Episode::$file = $request->file('file');

        $data = $request->only([
            'title',
            'program_id',
            'description'
        ]);

        $episode = new Episode($data);
        $episode->save();

        return response()->json(new EpisodeCollection($episode));
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
    public function edit(Episode $episode)
    {
        return view(
            'episodes.edit',
            [ 
                'episode' => $episode,
                'programs' => Program::all()
            ]
        );

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
        $episode = Episode::find($id);

        $data = $request->only([
          'title',
          'program_id',
          'description'
        ]);

        if ($request->hasFile('file')) {
            Episode::$file = $request->file('file');
        }

        $episode->update($data);

        $episodes = $this
            ->episode
            ->getAll();

        $programs = $this
            ->program
            ->getProgramsWithEpisodes($episodes, Auth::id());

        return response()->json($programs);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Episode $episode)
    {
        $program = Program::find($episode->program_id);

        $isOwnedByAuthUser = $program->first()->user_id == Auth::id();
        if (!$isOwnedByAuthUser) {
            return response()->json(401);
        }

        if ($episode->delete()) {
          return response()->json(204);
        } 

        return respnse()->json(404);
    }
}
