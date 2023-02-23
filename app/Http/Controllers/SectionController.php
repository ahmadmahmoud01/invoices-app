<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $sections = Section::all();

        return view('sections.index', compact('sections'));
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([

            'section_name' => 'required|unique:sections,section_name',
            'description' => 'required'

        ],[
            'section_name.required' => 'يرجى إدخال اسم القسم',
            'section_name.unique' => 'اسم القسم مسجل مسبقا',
            'description.required' => 'يرجى إدخال الوصف'
        ]);


        // $name_exists = Section::where('section_name', '=', $request->section_name)->exists();

        // if($name_exists) {

        //     session()->flash('error', 'خطأ القسم مسجل مسبقا');
        //     return redirect( route('sections.index') );

        // } else{

            section::create([
                'section_name' => $request->section_name,
                'description' => $request->description,
                'created_by' => Auth::user()->name
            ]);

            session()->flash('add', 'تم إضافة القسم بنجاح');
            return redirect( route('sections.index') );

        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->id;

        $this->validate($request, [

            'section_name' => 'required|max:255|unique:sections,section_name,'.$id,
            'description' => 'required',
        ],[

            'section_name.required' =>'يرجي ادخال اسم القسم',
            'section_name.unique' =>'اسم القسم مسجل مسبقا',
            'description.required' =>'يرجي ادخال الوصف',

        ]);

        $section = section::find($id);
        $section->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
        ]);

        session()->flash('edit','تم تعديل القسم بنجاج');
        return redirect(route('sections.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $section = Section::find($request->id)->delete();


        session()->flash('delete','تم حذف القسم بنجاج');
        return redirect(route('sections.index'));
    }
}
