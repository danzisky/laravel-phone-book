<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Phonebook;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\MessageBag;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if(Auth::check()) {
            $phonebook = Phonebook::where('id', $id)->get();
            $phonebook = $phonebook[0];          
            $user_id = Auth::user()->id;
            $contacts = [];
            $messages = array();
            if(!isset($phonebook['id']) || !isset($phonebook['user_id'])) {
                $message = 'sorry the phonebook could not be found';
            } elseif($phonebook['user_id'] != $user_id) {
                $message = 'sorry you are not auhtorized to manage this phonebook and its assets';
                array_push($messages, $message);
            } else {
                $contacts = Contact::where('phonebook_id', $id)->get();
            }
            return view('contact.contacts', ['user' => Auth::user(), 'phonebook' => $phonebook, 'contacts' => $contacts, 'messages', $messages]);        
        } else {
            return route('login');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($phonebook_id)
    {   
        $phonebook = Phonebook::where('id', $phonebook_id)->get();
        $phonebook = $phonebook[0];

        return view('contact.createcontact', ['user' => Auth::user(), 'phonebook' => $phonebook]);
    }
    public function createContact($phonebook_id) {

        $phonebook = Phonebook::where('id', $phonebook_id)->get();
        $phonebook = $phonebook[0];

        return view('contact.createcontact', ['user' => Auth::user(), 'phonebook' => $phonebook]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $phonebook_id)
    {
        $request->validate([ 
            'first_name' => ['string', 'nullable', 'max:20'],
            'last_name' => ['string', 'nullable', 'max:70'],
            'email' => ['string', 'email', 'nullable', 'max:70'],
            'phone' => ['string', 'nullable', 'max:70'],
            'addess1' => ['string', 'nullable', 'max:255'],
            'address2' => ['string', 'nullable', 'max:255'],
            'city' => ['string', 'nullable', 'max:255'],
            'state' => ['string', 'nullable', 'max:255'],
            'country' => ['string', 'nullable', 'max:255'],
            'zipcode' => ['string', 'nullable', 'max:255'],
            'notes' => ['string', 'nullable', 'max:255'],
        ]);
        
        if(Auth::check()) {
            $user_id = Auth::user()->id;  
            $messages = array();
            $error = new MessageBag();

            if ((!isset($request->first_name) && !isset($request->last_name)) || (empty($request->first_name) && empty($request->last_name))) {
                $message = array();
                $message['status'] = 'error';
                $message['message'] = 'please fill at least a name';

                $errors = $error->add('exists', $message['message']);
                return redirect()->route('phonebooks.contacts.create', ['phonebook' => $phonebook_id])->withErrors($errors)->withInput();
            } elseif ((!isset($request->email) && !isset($request->phone)) || (empty($request->email) && empty($request->phone))) {
                $message = array();
                $message['status'] = 'error';
                $message['message'] = 'please fill at least a contact detail';
                
                $errors = $error->add('exists', $message['message']);
                return redirect()->route('phonebooks.contacts.create', ['phonebook' => $phonebook_id])->withErrors($errors)->withInput();
            } else {
                $existingContact = Contact::where([
                    'phonebook_id' => $phonebook_id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,])->get();

                $phonebook = Phonebook::where('id', $phonebook_id)->get();
                $phonebook = $phonebook[0];          
                $user_id = Auth::user()->id;
    
                
    
                if(!isset($phonebook['id']) || !isset($phonebook['user_id'])) {
                    $message = array();
                    $message['status'] = 'error';
                    $message['message'] = 'sorry the phonebook could not be found '.$phonebook_id;
                    array_push($messages, $message);

                    $errors = $error->add('exists', $message['message']);
                    return redirect()->route('phonebooks.contacts.create', ['phonebook' => $phonebook_id])->withErrors($errors)->withInput();;
                } elseif($phonebook['user_id'] != $user_id) {
                    $message = array();
                    $message['status'] = 'error';
                    $message['message'] = 'sorry you are not auhtorized to manage this phonebook and its assets';
                    array_push($messages, $message);
                    $errors = $error->add('exists', $message['message']);
                    // return back()->withErrors($errors)->withInput();
                    return redirect()->route('phonebooks.contacts.create', ['phonebook' => $phonebook_id])->withErrors($errors)->withInput();
                } elseif(isset($existingContact[0]['id']) || isset($existingContact['id'])) {
                    $message = array();
                    $message['status'] = 'error';
                    $message['message'] = 'A contact with this name and exact contact details already exists';
                    array_push($messages, $message);

                    $errors = $error->add('exists', $message['message']);
                    // return back()->withErrors($errors)->withInput();
                    return redirect()->route('phonebooks.contacts.create', ['phonebook' => $phonebook_id])->withErrors($errors)->withInput();
                } else {
                    $contact = Contact::create([
                        'user_id' => $user_id,          
                        'phonebook_id' => $phonebook_id,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'address1' => $request->addess1,
                        'address2' => $request->address2,
                        'city' => $request->city,
                        'state' => $request->state,
                        'country' => $request->country,
                        'zipcode' => $request->zipcode,
                        'notes' => $request->notes,                
                    ]);
                    if(!$contact) {
                        $message = array();
                        $message['status'] = 'error';
                        $message['message'] = 'An unknown error occurred, contact could not be created. Pleasse try again later';
                        array_push($messages, $message);

                        $errors = $error->add('exists', $message['message']);
                        // return back()->withErrors($errors)->withInput();
                        return redirect()->route('phonebooks.contacts.create', ['phonebook' => $phonebook_id])->withErrors($errors)->withInput();
                    } else {
                        $message = array();
                        $message['status'] = 'success';
                        $message['message'] = 'New contact added successfully!';
                        array_push($messages, $message);
                    }
                    
                }
            }          
            
            $contacts = Contact::all();
            return view('contact.contacts', ['user' => Auth::user(), 'phonebook' => $phonebook, 'contacts' => $contacts, 'messages' => $messages]);
                       
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
            $contact = Contact::where('id', $id)->get();
            $contact = $contact[0];          
            $user_id = Auth::user()->id;
            $messages = array();
            if(!isset($contact['id']) || !isset($contact['user_id'])) {
                $message = array();
                $message['status'] = "error";
                $message = 'sorry the contact could not be found';
            }elseif($contact['user_id'] != $user_id) {
                $message = array();
                $message['status'] = "error";
                $message = 'sorry you are not auhtorized to manage view and manage this contact';
                array_push($messages, $message);
            } else {
                $phonebook = Phonebook::where('id', $contact['phonebook_id'])->get();
                $phonebook = $phonebook[0];
                $user = User::where('id', $contact['user_id'])->get();
                $user = $user[0];
            }
            return view('contact.contact', ['user' => $user, 'phonebook' => $phonebook, 'contact' => $contact, 'messages' => $messages]);        
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
