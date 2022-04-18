<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage your Phonebooks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if(count($errors) > 0)
                    @foreach($errors as $error)
                        <div class="p-6 w3-green w3-text-white-800 border-b border-gray-200">
                            Message: {{ $error }}
                        </div>
                    @endforeach
                @endif
            </div>
            <?php
            $phonebook = $phonebook;
            $phonebook_id = $phonebook['id'];
            $contacts = $contacts;
            $current_url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
            $location = 'phonebooks/shared/?phonebook_id=';
            $index = strpos($current_url, 'phonebook.php');
            $base_url = substr($current_url, 0, $index);
            $url = $base_url.$location.$phonebook_id;
            $link = 'http://'.$url;

            echo '<div class="w3-xxlarge w3-panel">Logged in as '.$user['first_name'].'</div>';
            echo '<div class="w3-xxlarge w3-panel">'.$phonebook['phonebook_name'].': Added Contacts</div>';

            echo '<div class="w3-large w3-panel"><div>To share this phonebook, use this link</div>';
            echo '<div class="w3-text-blue w3-link w3-medium">'.$link.'</div></div>'; 

            echo '<a href="account.php"><button  class="w3-medium w3-button w3-gray w3-margin-top w3-margin-bottom">BACK TO PHONEBOOKS</button></a>';
            echo '<br>';
            
            if(empty($contacts) || is_null($contacts)) {
                echo '<div class="w3-medium w3-panel w3-center">No contacts added yet</div>';
            }

            foreach ($contacts as $contact) {
                ?>
                <form action="contact.php" method="GET"/>
                    <button value="<?php echo $contact['id']; ?>" class="w3-button w3-light-grey w3-left-align">
                        <div><h3><?php echo $contact['first_name']." ".$contact['last_name']; ?></h3></div>
                        <div><h5><?php echo isset($contact['email']) ? "E-mail: ".$contact['email'] : ""; ?></h5></div>
                        <div><h5><?php echo isset($contact['phone']) ? "Mobile Number: ".$contact['phone'] : ""; ?></h5></div>
                    </button>
                    <input name='user_id' hidden type="hidden" value="<?php echo $user['user_id']; ?>" />
                    <input name='contact_id' hidden type="hidden" value="<?php echo $contact['id']; ?>" />
                </form>
                <div>
                    <form action="edit_contact.php" method="POST" class="w3-form"/>
                        <button value="<?php echo $contact['id']; ?> " class="w3-button w3-grey w3-left-align">EDIT</button>
                        <input name='contact_id' hidden type="hidden" value="<?php echo $contact['id']; ?>" />
                        <input name='user_id' hidden type="hidden" value="<?php echo $user['user_id']; ?>" />
                    </form>
                    <div class="w3-form"/>
                        <button id="<?php echo 'hide'.$contact['id']; ?>" value="1" pb_id="<?php echo $contact['id']; ?>" user_id="<?php echo $contact['user_id']; ?>" class="w3-button w3-green w3-left-align visible w3-margin-bottom" style="<?php echo ($contact['visible'] == "1" ? '' : 'display:none;'); ?>" onclick="hide(this)">HIDE</button>

                        <button id="<?php echo 'show'.$contact['id']; ?>" value="0" pb_id="<?php echo $contact['id']; ?>"  user_id="<?php echo $contact['user_id']; ?>" class="w3-button w3-yellow w3-left-align private w3-margin-bottom" style="<?php echo ($contact['visible'] != "1" ? '' : 'display:none;'); ?>" onclick="show(this)">UNHIDE</button>
                    </div>
                    <form action="contact/delete_contact.php" method="POST" class="w3-form"/>
                        <button name="delete" type="submit" value="<?php echo $contact['id']; ?>" class="w3-button w3-red w3-left-align">
                            DELETE
                        </button>
                        <input name='contact_id' hidden type="hidden" value="<?php echo $contact['id']; ?>" />
                        <input name='user_id' hidden type="hidden" value="<?php echo $user['user_id']; ?>" />
                    </form>
                </div>
                <hr>

                <?php
            }

            ?>
            <div  class="w3-margin-top w3-padding-bottom">
                <h3>Add New Contact</h3>
                <form action="contact/create_contact.php" method="POST">
                    <input class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name='first_name' type="text" placeholder="First Name" />
                    <input class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name='last_name' type="text" placeholder="Last Name" />
                    <input class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name='email' type="email" placeholder="email" />
                    <input class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name='phone' type="text" placeholder="Mobile Number" />
                    <textarea class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name='address1' type="text" placeholder="Adress 1" ></textarea>
                    <textarea class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name='address2' type="text" placeholder="Adress 2" ></textarea>
                    <input class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s6 m3 l3" name='city' type="text" placeholder="City" />
                    <input class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s6 m3 l3" name='state' type="text" placeholder="State" />
                    <input class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s6 m3 l3" name='zipcode' type="text" placeholder="Zip Code" />
                    <input class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name='country' type="text" value="Romania" />
                    <textarea class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name='notes' type="text" placeholder="Notes"></textarea>
                    <input class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name='Contact Group' type="text" placeholder="contact_group" />
                    <input class="contact-info" name='user_id' hidden type="hidden" value="<?php echo $user['user_id'] ?>" />
                    <input class="contact-info" name='phonebook_id' hidden type="hidden" value="<?php echo $phonebook_id; ?>" />
                    <br/>
                    <input class="w3-input w3-center w3-margin-right w3-green contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name="submit" type="submit" value="Create Contact"/>
                </form>
            </div>
            <br/>

            <script src="scripts/jquery.min.js"></script>
            <script>
                function changevisibleity(action, contact_id, user_id) {
                    var contact_id = contact_id
                    $.post("contact/toggle_contact.php",
                        {
                            contact_id: contact_id,
                            user_id: user_id,
                            action: action
                        },
                        function(data, status){					
                            data = JSON.parse(data);
                            messenger(data, contact_id);
                            //console.log(data.data);
                        }
                    );
                    function messenger(data, contact_id) {
                        togglePrivacy(contact_id, data.data.is_visible);                
                        alert(data.message);
                        console.log("contact_id");
                    }
                }
                function hide(element){
                    var contact_id = element.getAttribute('pb_id');
                    var user_id = element.getAttribute('user_id');
                    var action = "hide";
                    //console.log(element);
                    changevisibleity(action, contact_id, user_id);
                }
                function show(element){
                    var contact_id = element.getAttribute('pb_id');
                    var user_id = element.getAttribute('user_id');
                    var action = "show";
                    //console.log(element);
                    changevisibleity(action, contact_id, user_id);
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
                function togglePrivacy(contact_id, value) {
                    var id = contact_id;
                    var x = document.getElementById('show'+id);
                    var y = document.getElementById('hide'+id);
                    console.log(x);
                    console.log(y);
                    var is_hidden;
                    if(value == "0" || value == 0) {
                        is_hidden = true;
                    } else {
                        is_hidden = false;
                    }
                    if (is_hidden == true) {
                        /*y.className = y.className.replace(" w3-hide", " w3-show");
                        if (y.className.indexOf("w3-show") == -1) {                    
                            y.className += " w3-show";
                            x.className = x.className.replace(" w3-show", "");
                            x.className.indexOf("w3-hide") == -1 ? x.className += 'w3-hide' : x.className += '';
                                                
                        }*/
                        x.style.display = 'block';
                        y.style.display = 'none';
                    } else if (is_hidden == false) { 
                        /*x.className = x.className.replace(" w3-hide", " w3-show");
                        if (x.className.indexOf("w3-show") == -1) {                    
                            x.className += " w3-show";
                            y.className = x.className.replace(" w3-show", "");
                            y.className.indexOf("w3-hide") == -1 ? x.className += 'w3-hide' : x.className += '';                    
                        }*/
                        y.style.display = 'block';
                        x.style.display = 'none';
                    }
                }
                
                $(document).ready(function () {
                    console.log('document');
                });
            </script>

        </div>
    </div>
</x-app-layout>
