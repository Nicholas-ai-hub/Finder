<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    //showing all listings
    public function index(){
        return view('listings.index', [
            'heading' => 'First',
            'listings' => Listing::latest()->filter(request(['tag', 'search']))
            ->simplePaginate(2)
        ]);
    }

    //showing single listing
    public function show(Listing $listing){
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    //showing create form
    public function create(){
        return view('listings.create');
    }

    //storing listing
    public function store(Request $request){
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required',
        ]);

        //if file is uploaded store to storage/app/public/logos folder
        if($request->hasFile('logo')){
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        //Listing ownership
        $formFields['user_id'] = auth()->id();

        Listing::create($formFields);

        return redirect('/')->with('message', 'Listing created successfully!');
    }

    //showing edit form
    public function edit(Listing $listing){
        return view('listings.edit', [
        'listing'=>$listing
        ]);
    }

    //editing form
    public function update(Request $request,Listing $listing){

        // Make sure logged in user owner
        if($listing->user_id != auth()->id()){
            abort(403, 'Unauthorized Action');
        }

        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required'],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required',
        ]);

        if($request->hasFile('logo')){
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $listing->update($formFields);

        return back()->with('message', 'Listing updated successfully!');
    }

    //deleting listings
    public function destroy(Listing $listing){
        if($listing->user_id != auth()->id()){
            abort(403, 'Unauthorized Action');
        }
        $listing->delete();

        return redirect('/')->with('message', 'Listing deleted!');
    }

    //Manage listings
    public function manage(){
        return view('listings.manage', [
            'listings' => auth()->user()->listings()->get()
        ]);
    }
}