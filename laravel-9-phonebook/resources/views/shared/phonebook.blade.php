@extends('layouts.app2')
    @section('header')
    <div name="header">
        @if($access == true)
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user['name'] }}'s Shared Phonebook
        </h2>
        @endif
        @if($access != true)
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Sorry phonebook could not be retrieved
        </h2>
        @endif
    </div>
    @endsection
    @section('slot')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if($access == true)
            <?php
              
            $phonebook_id = $phonebook['id'];
            $contacts = $contacts;
            $link = route('phonebook', ['id' => $phonebook['id']]);
            echo '<div class="w3-xlarge w3-panel">Phonebook name: '.$phonebook['phonebook_name'].'</div>';

            echo '<div class="w3-large w3-panel"><div>To share this phonebook, use this link</div>';
            echo '<div class="w3-text-blue w3-link w3-medium">'.$link.'</div></div>'; 

                       
            if(empty($contacts) || is_null($contacts)) {
                echo '<div class="w3-medium w3-panel w3-center">No contacts added yet</div>';
            }

            ?>
            @foreach ($contacts as $contact)
                <a href="{{ route('contact', ['id' => $contact['id']]) }}"/>
                    @csrf
                    <button value="<?php echo $contact['id']; ?>" class="w3-button w3-light-grey w3-left-align">
                        <div><h3><?php echo $contact['first_name']." ".$contact['last_name']; ?></h3></div>
                        <div><h5><?php echo isset($contact['email']) ? "E-mail: ".$contact['email'] : ""; ?></h5></div>
                        <div><h5><?php echo isset($contact['phone']) ? "Mobile Number: ".$contact['phone'] : ""; ?></h5></div>
                    </button>
                </a>
                <div>
                    <div class="w3-form"/>
                        <button onclick="alert('contact saved :)')" class="w3-button w3-grey w3-left-align w3-margin-bottom">SAVE</button>
                    </div>
                </div>
                <hr>
            @endforeach     
        @endif
        </div>
    </div>
    @endsection
