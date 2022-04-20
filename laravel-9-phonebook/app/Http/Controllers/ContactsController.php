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
                    $message['message'] = 'sorry the phonebook could not be found';
                    array_push($messages, $message);

                    $errors = $error->add('exists', $message['message']);
                    return redirect()->route('phonebooks.contacts.create', ['phonebook' => $phonebook_id])->withErrors($errors)->withInput();
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
        $contact = Contact::where('id', $id)->get();
        $contact = $contact[0];
        $phonebook = Phonebook::where('id', $contact['phonebook_id'])->get();
        $phonebook = $phonebook[0];
        $messages = array();
        $error = new MessageBag();
        if ($contact['user_id'] != Auth::user()->id) {
            $message = array();
            $message['status'] = "error";
            $message['message'] = 'sorry you are not auhtorized to manage this phonebook and its assets';
            array_push($messages, $message);
            
            $errors = $error->add('unauthorized', $message['message']);
            return redirect()->route('phonebooks.index')->withErrors($errors);

        } else {
            return view('contact.editcontact', ['user' => Auth::user(), 'contact' => $contact, 'phonebook' => $phonebook]);
        }
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
            $contact = Contact::where(['id' => $id,
                'user_id' => $user_id,
            ])->get();
            $contact = $contact[0];
            $phonebook = Phonebook::find($contact['phonebook_id']);
            $error = new MessageBag();

            if ((!isset($request->first_name) && !isset($request->last_name)) || (empty($request->first_name) && empty($request->last_name))) {
                $message = array();
                $message['status'] = 'error';
                $message['message'] = 'please fill at least a name';

                $errors = $error->add('exists', $message['message']);
                return back()->withErrors($errors)->withInput();
            } elseif ((!isset($request->email) && !isset($request->phone)) || (empty($request->email) && empty($request->phone))) {
                $message = array();
                $message['status'] = 'error';
                $message['message'] = 'please fill at least a contact detail';
                
                $errors = $error->add('exists', $message['message']);
                return back()->withErrors($errors)->withInput();
            } else {
                $phonebook_id = $phonebook['id'];
                $existingContact = Contact::where([
                    'phonebook_id' => $phonebook_id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,])->get();

                if(!isset($contact['id']) || !isset($contact['user_id'])) {
                    $message = array();
                    $message['status'] = 'error';
                    $message['message'] = 'sorry the contact could not be found';
                    array_push($messages, $message);

                    $errors = $error->add('exists', $message['message']);
                    return back()->withErrors($errors)->withInput();
                } elseif($contact['user_id'] != $user_id) {
                    $message = array();
                    $message['status'] = 'error';
                    $message['message'] = 'sorry you are not auhtorized to manage this asset';
                    array_push($messages, $message);
                    $errors = $error->add('exists', $message['message']);
                    // return back()->withErrors($errors)->withInput();
                    return redirect()->route('contacts.edit', ['contact' => $contact['id']])->withErrors($errors)->withInput();
                } elseif(isset($existingContact[0]['id']) && $existingContact[0]['id'] != $id) {
                    $message = array();
                    $message['status'] = 'error';
                    $message['message'] = 'A contact with this name and exact contact details already exists';
                    array_push($messages, $message);

                    $errors = $error->add('exists', $message['message']);
                    return back()->withErrors($errors)->withInput();
                } else {
                    $contact = Contact::where('id', $id)->update([
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
                        $message['message'] = 'An unknown error occurred, contact could not be updated. Pleasse try again later';
                        array_push($messages, $message);

                        $errors = $error->add('exists', $message['message']);
                        return back()->withErrors($errors)->withInput();
                    } else {
                        $message = array();
                        $message['status'] = 'success';
                        $message['message'] = 'Changes saved successfully for '.$request->first_name.' '.$request->lastname;
                        array_push($messages, $message);

                        $errors = $error->add('success', $message['message']);
                        return redirect()->route('phonebooks.contacts.index', ['phonebook' => $phonebook['id']])->withErrors($errors)->withInput();
                    }   
                }
            }       
                           
        } else {
            return route('login');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::check()) {
            $contact = Contact::where('id', $id)->get();
            $contact = $contact[0];
            $contact_name = $contact['first_name'].' '.$contact['last_name'];
            $phonebook_id = $contact['phonebook_id'];          
            $user_id = Auth::user()->id;
            $error = new MessageBag();
            if (empty($contact['id']) || is_null($contact['id'])) {
                $message = array();
                $message['status'] = "error";
                $message['message'] = 'sorry the phonebook could not be found';
            } else {
                if ($contact['user_id'] != $user_id) {
                    $message = array();
                    $message['status'] = "error";
                    $message['message'] = 'delete failed, sorry you are not auhtorized to manage this phonebook and its assets';

                    $errors = $error->add('unauthorized', $message['message']);
                    return redirect()->route('phonebooks.contacts.index', ['phonebook' => $phonebook_id])->withErrors($errors);
                } else {
                    $deletion = Contact::destroy($contact['id']);
                    if($deletion == false) {
                        $message = array();
                        $message['status'] = "error";
                        $message['message'] = 'unknown error occurred, could not perform the request';
                        
                        $errors = $error->add('unknown', $message['message']);
                        return redirect()->route('phonebooks.contacts.index', ['phonebook' => $phonebook_id])->withErrors($errors);
                    } else {
                        $message = array();
                        $message['status'] = "success";
                        $message['message'] = 'successfully deleted the contact for '.$contact_name;
                        
                        $errors = $error->add('unknown', $message['message']);
                        return redirect()->route('phonebooks.contacts.index', ['phonebook' => $phonebook_id])->withErrors($errors);
                    }
                }
            }  
            
        } else {
            return route('login');
        }
    }

    public function changeVisibility(Request $request) {
        if(isset($request->action) && isset($request->user_id)) {
            
            $contact_id = $request->input('contact_id');
            $user_id = $request->input('user_id');
            $action = $request->input('action');

            $existingContact = Contact::where(['id' => $contact_id, 
                    'user_id' => $user_id,
                ])->get();

            function generateResJSON($status, $data=['data' => null], $message) {
                $response = array();
                $response['status'] = $status;
                $response['data'] = $data;
                $response['message'] = $message;
                // $response = json_encode($response);
                return $response;
            }

            
            if(empty($existingContact[0]['id']) || is_null($existingContact[0]['id'])) {
                $response = generateResJSON('error', ["conact" => null], 'The contact does not exist');
            } else {
                
                $action == 'hide' ? $hidden = true : $hidden = false;
                
                $ifUpdated = Contact::where('id', $contact_id)->update([
                        'hidden' => $hidden,
                    ]);
                if(!$ifUpdated) {
                   $response = generateResJSON('error', ["contact" => null], 'The visibility of contact wasn\'t changed due to unknown errors');
                } elseif($ifUpdated) {
                    $existingContact = Contact::where(['id' => $contact_id, 
                        'user_id' => $user_id,
                    ])->get();
                    $response = generateResJSON('success', ["is_hidden" => $existingContact[0]['hidden']], 'The hidden status of phonebook was successfully changed');
                }
            }
            return response()->json($response);
        }
    }
    public function getContact ($id) {
        $messages = array();
        if(is_numeric($id)) {
            $contact = Contact::where('id', $id)->get();       
            
            if(!isset($contact[0]['id']) || !isset($contact[0]['user_id'])) {                
                $message = array();
                $message['status'] = "error";
                $message['message'] = 'sorry the contact could not be found';
                array_push($messages, $message);
                return view('shared.contact', ['access' => false, 'messages' => $messages]);
            } if($contact[0]['hidden'] == true) {
                $message = array();
                $message['status'] = "error";
                $message['message'] = 'sorry this contact is not publicly available';
                array_push($messages, $message);
                return view('shared.contact', ['access' => false, 'messages' => $messages]); 
            } else {
                $contact = $contact[0];
                $phonebook = Phonebook::where('id', $contact['phonebook_id'])->get();
                $phonebook = $phonebook[0];
                $user = User::where('id', $contact['user_id'])->get();
                $user = $user[0];
            }
            return view('shared.contact', ['access' => true, 'user' => $user, 'phonebook' => $phonebook, 'contact' => $contact, 'messages' => $messages]);        
        } else {
            $message = array();
            $message['status'] = "error";
            $message['message'] = 'Broken link: please enter a numeric id';
            array_push($messages, $message);
            return view('shared.contact', ['access' => false, 'messages' => $messages]);
        }
    }
}
