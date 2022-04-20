<?php

namespace App\Http\Controllers;

use App\Models\Phonebook;
use App\Models\Contact;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isFalse;

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
            $existingPB = Phonebook::where(['phonebook_name' => $name,
                    'user_id' => $user_id,
                ])->get();
            $messages = array();
            $error = new MessageBag;
            if(isset($existingPB[0]['id']) || isset($existingPB['id'])) {
                $message = array();
                $message['status'] = "error";
                $message['message'] = 'A phonebook with the name '.$request->name.' already exists, please use another name for this phonebook';
                array_push($messages, $message);
                //return route('phonebooks/create', ['user' => Auth::user(), 'messages' => $messages]);
                
                $errors = $error->add('exists', $message['message']);
                return back()->withErrors($errors)->withInput();
            } else {
                $phonebook = Phonebook::create([
                    'user_id' => $user_id,
                    'phonebook_name' => $name,
                    'phonebook_description' => $description,                
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
                $message = array();
                $message['status'] = "error";
                $message['message'] = 'sorry the phonebook could not be found';
                array_push($messages, $message);
            }elseif($phonebook[0]['user_id'] != $user_id) {
                $message = array();
                $message['status'] = "error";
                $message['message'] = 'sorry you are not auhtorized to manage this phonebook and its assets';
                array_push($messages, $message);
            } else {
                $contacts = Contact::where('phonebook_id', $id)->get();
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
        $phonebook = Phonebook::find($id)->first();
        $messages = array();
        $error = new MessageBag();
        if ($phonebook['user_id'] != Auth::user()->id) {
            $message = array();
            $message['status'] = "error";
            $message['message'] = 'sorry you are not auhtorized to manage this phonebook and its assets';
            array_push($messages, $message);
            
            $errors = $error->add('unauthorized', $message['message']);
            return redirect()->route('phonebooks.index')->withErrors($errors);

        } else {
            return view('phonebook.editphonebook', ['user' => Auth::user(), 'phonebook' => $phonebook])->with('phonebook', $phonebook);
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
            'name' => ['required', 'string', 'max:20'],
            'description' => ['string', 'nullable', 'max:255'],
        ]);

        if(Auth::check()) {
            $user_id = Auth::user()->id;
            $name = $request->name;
            $description = $request->description;
            $phonebook = Phonebook::where(['id' => $id,
                'user_id' => $user_id,
            ])->get();
            $existingPB = Phonebook::where(['phonebook_name' => $name,
                    'user_id' => $user_id,
                ])->get();
            $messages = array();
            $error = new MessageBag;
            if(!isset($phonebook['id']) && !isset($phonebook[0]['id'])) {
                $message = array();
                $message['status'] = "error";
                $message['message'] = 'The requested phonebook does not exists for your account';
                array_push($messages, $message);

                $errors = $error->add('exists', $message['message']);
                return back()->withErrors($errors)->withInput();
            } if(isset($existingPB[0]['id']) || isset($existingPB['id'])) {
                $message = array();
                $message['status'] = "error";
                $message['message'] = 'A phonebook with the name '.$request->name.' already exists, please use another name for this phonebook';
                array_push($messages, $message);

                $errors = $error->add('exists', $message['message']);
                return back()->withErrors($errors)->withInput();
            } else {
                $phonebook = Phonebook::where('id', $id)
                    ->update([
                        'phonebook_name' => $name,
                        'phonebook_description' => $description,                
                    ]);

                if(!$phonebook) {
                    $message = array();
                    $message['status'] = "error";
                    $message['message'] = 'An unknown error occurred, phonebook could not be updated. Pleasse try again later';
                    array_push($messages, $message);

                    $errors = $error->add('exists', $message['message']);
                    return back()->withErrors($errors)->withInput();
                } else {
                    $message = array();
                    $message['status'] = "success";
                    $message['message'] = 'The phonebook has been updated successfully';
                    array_push($messages, $message);

                    $errors = $error->add('success', $message['message']);
                    return redirect()->route('phonebooks.index')->withErrors($errors)->withInput();
                }
                
            }
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
            $phonebook = Phonebook::where('id', $id)->get();
            $phonebook = $phonebook[0];
            $phonebook_name = $phonebook['phonebook_name'];          
            $user_id = Auth::user()->id;
            $error = new MessageBag();
            if (empty($phonebook['id']) || is_null($phonebook['id'])) {
                $message = array();
                $message['status'] = "error";
                $message['message'] = 'sorry the phonebook could not be found';
            } else {
                if ($phonebook['user_id'] != $user_id) {
                    $message = array();
                    $message['status'] = "error";
                    $message['message'] = 'delete failed, sorry you are not auhtorized to manage this phonebook and its assets';

                    $errors = $error->add('unauthorized', $message['message']);
                    return redirect()->route('phonebooks.index')->withErrors($errors);
                } else {
                    $deletion = Phonebook::destroy($phonebook['id']);
                    if($deletion == false) {
                        $message = array();
                        $message['status'] = "error";
                        $message['message'] = 'unknown error occurred, could not perform the request';
                        
                        $errors = $error->add('unknown', $message['message']);
                        return redirect()->route('phonebooks.index')->withErrors($errors);
                    } else {
                        $message = array();
                        $message['status'] = "success";
                        $message['message'] = 'successfully deleted the phonebook '.$phonebook_name;
                        
                        $errors = $error->add('unknown', $message['message']);
                        return redirect()->route('phonebooks.index')->withErrors($errors);
                    }
                }
            }  

        } else {
            return route('login');
        }
    }
    public function changePublicity(Request $request) {
        if(isset($request->action) && isset($request->user_id)) {
            
            $phonebook_id = $request->input('phonebook_id');
            $user_id = $request->input('user_id');
            $action = $request->input('action');

            $existingPhonebook = Phonebook::where(['id' => $phonebook_id, 
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

            
            if(empty($existingPhonebook[0]['id']) || is_null($existingPhonebook[0]['id'])) {
                $response = generateResJSON('error', ["phonebook" => null], 'phonebook does not exist');
            } else {
                
                $action == 'share' ? $public = true : $public = false;
                
                $ifUpdated = Phonebook::where('id', $phonebook_id)->update([
                        'public' => $public,
                    ]);
                if(!$ifUpdated) {
                   $response = generateResJSON('error', ["phonebook" => null], 'publicity of phonebook wasn\'t changed due to unknown errors');
                } elseif($ifUpdated) {
                    $existingPhonebook = Phonebook::where(['id' => $phonebook_id, 
                        'user_id' => $user_id,
                    ])->get();
                    $response = generateResJSON('success', ["is_public" => $existingPhonebook[0]['public']], 'publicity of phonebook was successfully changed');
                }
            }
            return response()->json($response);
        }
    }
    public function getPhonebook ($id) {
        $messages = array();
        if(is_numeric($id)) {
            $phonebook = Phonebook::where('id', $id)->get();
            $contacts = [];
            
            if(!isset($phonebook[0]['id'])) {
                $message = array();
                $message['status'] = "error";
                $message['message'] = 'sorry the phonebook could not be found';
                array_push($messages, $message);

                return view('shared.phonebook', ['access' => false, 'messages' => $messages]);
            } elseif($phonebook[0]['public'] == false) {
                $message = array();
                $message['status'] = "error";
                $message['message'] = 'Sorry this phonebook is not publicly available';
                array_push($messages, $message);

                return view('shared.phonebook', ['access' => false, 'messages' => $messages]);
            } else {
                $phonebook = $phonebook[0];          
                $user = User::where('id', $phonebook['user_id'])->get()[0];
                $contacts = Contact::where(['phonebook_id' => $id, 'hidden' => false])->get();
            }
            return view('shared.phonebook', ['access' => true, 'user' => $user, 'phonebook' => $phonebook, 'contacts' => $contacts, 'messages', $messages]);         
        } else {
            $message = array();
            $message['status'] = "error";
            $message['message'] = 'Broken link: please enter a numeric id';
            array_push($messages, $message);
            return view('shared.phonebook', ['access' => false, 'messages' => $messages]);
        }
    }
}
