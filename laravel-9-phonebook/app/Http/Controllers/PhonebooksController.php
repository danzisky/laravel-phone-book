<?php

namespace App\Http\Controllers;

use App\Models\Phonebook;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;

class PhonebooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::check()) {
            $phonebooks = Phonebook::all();
            return view('phonebook.phonebooks', ['user' => Auth::user(), 'phonebooks' => $phonebooks]);            
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
        return view('phonebook.createphonebook', ['user' => Auth::user(),]);
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
            'name' => ['required', 'string', 'max:20'],
            'description' => ['string', 'nullable', 'max:255'],
        ]);

        if(Auth::check()) {
            $user_id = Auth::user()->id;
            $name = $request->name;
            $description = $request->description;
            $existingPB = Phonebook::where('phonebook_name', $name)->get();
            $messages = array();
            $error = new MessageBag;
            if(isset($existingPB[0]['id']) || isset($existingPB['id'])) {
                $message = array();
                $message['status'] = "error";
                $message['message'] = 'A phonebook with this name already exists, please use another name for this phonebook';
                array_push($messages, $message);
                //return route('phonebooks/create', ['user' => Auth::user(), 'messages' => $messages]);
                
                $errors = $error->add('exists', $message['message']);
                return back()->withErrors($errors)->withInput();
            } else {
                $phonebook = Phonebook::create([
                    'user_id' => $user_id,
                    'phonebook_name' => $name,
                    'phonebook_desriptiom' => $description,                
                ]);
                if(!$phonebook) {
                    $message = array();
                    $message['status'] = "error";
                    $message['message'] = 'An unknown error occurred, phonebook could not be created. Pleasse try again later';
                    array_push($messages, $message);
                    
                    $errors = $error->add('exists', $message['message']);
                    return back()->withErrors($errors)->withInput();
                } else {
                    $message = array();
                    $message['status'] = "success";
                    $message['message'] = 'A new phonebook has been created successfully';
                    array_push($messages, $message);
                }
                
            }
            $phonebooks = Phonebook::all();
            return view('phonebook.phonebooks', ['user' => Auth::user(), 'phonebooks' => $phonebooks, 'messages' => $messages]);
                       
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
        if(Auth::check()) {
            $phonebook = Phonebook::where('id', $id)->get();
            $phonebook = $phonebook[0];          
            $user_id = Auth::user()->id;
            $contacts = [];
            $messages = array();
            if(!isset($phonebook[0]['id']) || !isset($phonebook['user_id'])) {
                $message = 'sorry the phonebook could not ne found';
            }elseif($phonebook[0]['user_id'] != $user_id) {
                $message = 'sorry you are not auhtorized to manage this phonebook and its assets';
                array_push($messages, $message);
            } else {
                $contacts = Contact::where('phonebook_id', $id)->all();
            }
            return view('contact.contacts', ['user' => Auth::user(), 'phonebook' => $phonebook, 'contacts' => $contacts, 'messages', $messages]);   
            echo 'reached';         
        } else {
            return route('login');
        }
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
