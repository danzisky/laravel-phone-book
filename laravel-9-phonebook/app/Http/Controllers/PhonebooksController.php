<?php

namespace App\Http\Controllers;

use App\Models\Phonebook;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\AssignOp\Concat;

class PhonebooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
        if(Auth::check()) {
            $phonebook = Phonebook::where('id', $id)->get();            
            $user_id = Auth::user()->id;
            $contacts = [];
            $errors = array();
            if($phonebook['user_id'] != $user_id) {
                $error = 'sorry you are not auhtorized to manage this phonebook and its assets';
                array_push($errors, $error);
            } else {
                $contacts = Contact::where('phonebook_id', $id)->all();
            }
            return view('contact.contacts', ['user' => Auth::user(), 'phonebook' => $phonebook, 'contacts' => $contacts, 'errors', $errors]);            
        } else {
            return route('login');
        }
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
        //
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['string', 'max:255'],
        ]);

        if(Auth::check()) {
            $user_id = Auth::user()->id;
            $name = $request->name;
            $description = $request->description;
            $existingPB = Phonebook::where('phonebook_name', $name)->get();
            $errors = array();
            if(isset($existingPB[0]['id']) || isset($existingPB['id'])) {
                $error = 'A phonebook with this name already exists, please use another name for this phonebook';
                array_push($errors, $error);
            } else {
                $phonebook = Phonebook::create([
                    'user_id' => $user_id,
                    'phonebook_name' => $name,
                    'phonebook_desriptiom' => $description,                
                ]);
                if(!$phonebook) {
                    $error = 'An unknown error occurred, phonebook could not be created. Pleasse try again later';
                }
                
            }
            $phonebooks = Phonebook::all();
            return view('phonebook.phonebooks', ['user' => Auth::user(), 'phonebooks' => $phonebooks, 'errors' => $errors]);
                       
        } else {
            return route('login');
        }
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
