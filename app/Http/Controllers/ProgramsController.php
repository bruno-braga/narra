<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Repository\LanguageRepositoryInterface;

use App\Http\Resources\ProgramCollection;

use App\Program;
use App\Category;

class ProgramsController extends Controller
{
    private $program;
    private $language;

    public function __construct(LanguageRepositoryInterface $language)
    {
        $this->middleware('auth');
        $this->language = $language;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programs = Program::select(['programs.id', 'programs.title', 'programs.slug'])
            ->where('programs.user_id', Auth::id())
            ->with([
                'images' => function($query) {
                    $query->select('imageable_id', DB::raw('CONCAT(images.path, images.filename) as path'));
                },
            ])
            ->get()
            ->toArray();

        return view(
            'programs.index',
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
        $parentCategories = Category::where('parent_id', null)
            ->get()
            ->toArray();

        $childCategories = Category::where('parent_id', '!=', null)
            ->get()
            ->toArray();


        return view(
            'programs.create',
            [
                'parentCategories' => $parentCategories,
                'childCategories' => $childCategories,
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
        Program::$file = $request->file('file');
        $data = $request->only(['title', 'description']);

        $data['user_id'] = Auth::id();

        $program = new Program($data);
        $program->save();

        $program->categories()->attach($request->input('category_id'));

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
    public function edit(Program $program)
    {
        $parentCategories = Category::where('parent_id', null)
            ->get()
            ->toArray();

        $childCategories = Category::where('parent_id', '!=', null)
            ->get()
            ->toArray();

        return view(
            'programs.edit',
            [ 
                'program' => $program->load('categories'),
                'parentCategories' => $parentCategories,
                'childCategories' => $childCategories
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

        $categoryId = $request->input('category_id');

        if ($request->hasFile('file')) {
          Program::$file = $request->file('file');
        }

        $program->update($data);
        $program->categories()->sync($categoryId);

        return response()->json(['success' => true]);
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

        if (!$program->delete()) {
            return respnse()->json(404);
        }
        
        return response()->json(204);
    }

    public function settings(int $id)
    {
        return view(
            'settings.index',
            [
                'languages' => $this->language->getAll(),
                'id' => $id
            ]
        );
    }

    public function storeSettings(Request $request, Program $program)
    {
        return $program
            ->settings()
            ->create($request->input('settings'));
    }
}
