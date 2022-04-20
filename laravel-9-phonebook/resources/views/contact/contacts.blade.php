<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage your Contacts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w3-container">
            
            <?php
            $phonebook = $phonebook;
            $phonebook_id = $phonebook['id'];
            $contacts = $contacts;
            
            $link = route('phonebook', ['id' => $phonebook['id']]);

            echo '<div class="w3-xlarge w3-panel">'.$phonebook['phonebook_name'].': Added Contacts</div>';

            echo '<div class="w3-large w3-panel"><div>To share this phonebook, use this link</div>';
            echo '<div class="w3-text-blue w3-link w3-medium">'.$link.'</div></div>'; 

            echo '<div class="w3-margin-bottom"><a href="'. route('phonebooks.contacts.create', ['phonebook' => $phonebook['id']]).'"><input class="w3-btn w3-green w3-center w3-margin-top" name="submit" type="submit" value="CREATE NEW CONTACT"/></a></div>';

            echo '<a href="'.route('phonebooks.index').'" class=""><button  class="w3-medium w3-button w3-gray w3-margin-top w3-margin-bottom">BACK TO PHONEBOOKS</button></a>';
            echo '<br>';
            
            if(empty($contacts) || is_null($contacts)) {
                echo '<div class="w3-medium w3-panel w3-center">No contacts added yet</div>';
            }

            foreach ($contacts as $contact) {
                ?>
                <div class=" w3-padding w3-row-padding w3-white w3-margin-bottom w3-leftbar w3-border-gray">
                        <div class="w3-col s12 m7 w3-white w3-row">
                            <a href="{{ route('contacts.show', ['contact' => $contact['id']]) }}"/>
                                @csrf
                                <button value="<?php echo $contact['id']; ?>" class="w3-button w3-col s12 w3-light-grey w3-left-align">
                                    <div><h3><?php echo $contact['first_name']." ".$contact['last_name']; ?></h3></div>
                                    <div><h5><?php echo isset($contact['email']) ? "E-mail: ".$contact['email'] : ""; ?></h5></div>
                                    <div><h5><?php echo isset($contact['phone']) ? "Mobile Number: ".$contact['phone'] : ""; ?></h5></div>
                                </button>
                            </a>
                        </div>
                    <div class="w3-row-padding w3-col s12 m5 w3-margin-top">
                        <form action="{{ route('contacts.edit', ['contact' => $contact['id']]) }}" method="GET" class="w3-col s3 m12">
                            @csrf
                            <button value="<?php echo $contact['id']; ?> " class="w3-button w3-grey w3-left-align w3-margin-bottom">EDIT</button>
                        </form>
                        <div class="w3-col s3 m12">
                            <button id="<?php echo 'hide'.$contact['id']; ?>" value="1" contact_id="<?php echo $contact['id']; ?>" user_id="<?php echo $contact['user_id']; ?>" class="w3-button w3-green w3-left-align visible w3-margin-bottom" style="<?php echo ($contact['hidden'] != "1" ? '' : 'display:none;'); ?>" onclick="hide(this)">HIDE</button>

                            <button id="<?php echo 'show'.$contact['id']; ?>" value="0" contact_id="<?php echo $contact['id']; ?>"  user_id="<?php echo $contact['user_id']; ?>" class="w3-button w3-yellow w3-left-align private w3-margin-bottom" style="<?php echo ($contact['hidden'] == "1" ? '' : 'display:none;'); ?>" onclick="show(this)">UNHIDE</button>
                        </div>
                        <form action="{{ route('contacts.destroy', ['contact' => $contact['id']]) }}" method="POST" class="w3-col s3 m12">
                            @csrf
                            @method('delete')
                            <button name="delete" type="submit" value="<?php echo $contact['id']; ?>" class="w3-button w3-red w3-left-align">
                                DELETE
                            </button>
                        </form>
                    </div>
                </div>
                <hr>

                <?php
            }

            ?>
                       
            <br/>

            <script src="{{ asset('js/jquery.min.js') }}"></script>
            <script>
                function changeVisibility(action, contact_id, user_id) {
                    var contact_id = contact_id
                    var data = {
                            contact_id: contact_id,
                            user_id: user_id,
                            action: action
                        };
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: "{{ route('visibility') }}",
                        data: data,
                        dataType: "json",
                        success: function (response) {
                            // console.log(response);
                            messenger(response, contact_id);
                        }
                    });
                    
                    function messenger(data, contact_id) {
                        toggleVisibility(contact_id, data.data.is_hidden);                
                        alert(data.message);
                        console.log("contact_id");
                    }
                }
                function hide(element){
                    var contact_id = element.getAttribute('contact_id');
                    var user_id = element.getAttribute('user_id');
                    var action = "hide";
                    //console.log(element);
                    changeVisibility(action, contact_id, user_id);
                }
                function show(element){
                    var contact_id = element.getAttribute('contact_id');
                    var user_id = element.getAttribute('user_id');
                    var action = "show";
                    //console.log(element);
                    changeVisibility(action, contact_id, user_id);
                }
                function toggle(id) {
                    var x = document.getElementById('show'+id);
                    var y = document.getElementById('hide'+id);
                    if (x.className.indexOf("w3-show") == -1) {
                        x.className += " w3-show";
                        y.className = y.className.replace(" w3-show", "w3-hide");
                    } else { 
                        x.className = x.className.replace(" w3-show", "w3-hide");
                        y.className += " w3-show";
                    }
                }
                function toggleVisibility(contact_id, value) {
                    var id = contact_id;
                    var x = document.getElementById('show'+id);
                    var y = document.getElementById('hide'+id);
                    console.log(x);
                    console.log(y);
                    var is_hidden;
                    if(value == "1" || value == 1) {
                        is_hidden = true;
                    } else {
                        is_hidden = false;
                    }
                    if (is_hidden == true) {
                        
                        x.style.display = 'block';
                        y.style.display = 'none';
                    } else if (is_hidden == false) {

                        y.style.display = 'block';
                        x.style.display = 'none';
                    }
                }
            </script>

        </div>
    </div>
</x-app-layout>
