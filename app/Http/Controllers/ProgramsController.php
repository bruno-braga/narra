<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\ProgramCollection;
use App\Repository\ProgramRepositoryInterface;

use App\Program;

class ProgramsController extends Controller
{
    private $program;

    public function __construct(ProgramRepositoryInterface $program)
    {
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
        return view(
            'programs.index',
            [
                'programs' => $this->program->getAll()
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
        return view('programs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Program::$file = $request->file('file');
        $data = $request->only(['title', 'description']);

        $data['user_id'] = Auth::id();

        $program = new Program($data);
        $program->save();

        return response()->json(new ProgramCollection($program));
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
        return view(
            'programs.edit',
            [ 
                'program' => Program::find($id)
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
        $program = Program::find($id);

        $data = $request->only([
          'title',
          'description'
        ]);

        if ($request->hasFile('file')) {
          Program::$file = $request->file('file');
        }

        $program->update($data);

        return response()->json($this->program->getAll());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program $program)
    {
        $isOwnedByAuthUser = $program->first()->user_id == Auth::id();
        if (!$isOwnedByAuthUser) {
            return response()->json(401);
        }

        if ($program->images->exists()) {
            if ($program->delete()) {
                $program->destroyFile($program->images->image_path);
            } else {
                return respnse()->json(404);
            }
        }
        
        return response()->json(204);
    }
}
