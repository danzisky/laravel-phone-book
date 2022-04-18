<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage your Phonebooks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!
                </div>
            </div>
            <?php
            $phonebooks = $phonebooks;
            $current_url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
            $location = 'phonebooks/shared/?phonebook_id=';
            $index = strpos($current_url, 'account.php');
            $base_url = substr($current_url, 0, $index);
            $url = 'http://'.$base_url.$location;

            echo '<div class="w3-xxlarge w3-panel">Welcome '.$user['name'].'</div>';
            echo '<div class="w3-xxlarge w3-panel">Created Phonebooks</div>';

            if(empty($phonebooks) || is_null($phonebooks)) {
                echo '<div class="w3-medium w3-panel w3-center">No Phone Book created yet</div>';
            }

            foreach ($phonebooks as $phonebook) {
                ?>
                <form action="/phonebook/create" method="GET" class="w3-form"/>
                    <button value="<?php echo $phonebook['id']; ?> " class="w3-button w3-light-grey w3-left-align">
                        <div><h3><?php echo $phonebook['phonebook_name']; ?></h3></div>
                        <div><h5><?php echo $phonebook['phonebook_description']; ?></h5></div>
                        
                        
                    </button>
                    
                    <input name='phonebook_id' hidden type="hidden" value="<?php echo $phonebook['id']; ?>" />
                    <input name='user_id' hidden type="hidden" value="<?php echo $user['id']; ?>" />
                </form>
                <div class="w3-small w3-margin w3-text-hover-blue w3-text-blue"><a href="<?php echo $url.$phonebook['id']; ?>" target="_blank">VIEW PHONEBOOK THROUGH LINK</a></div>
                <div>
                    <form action="edit_phonebook.php" method="POST" class="w3-form"/>
                        <button name="submit" type="submit" value="<?php echo $phonebook['id']; ?> " class="w3-button w3-grey w3-left-align">EDIT</button>
                        <input name='phonebook_id' hidden type="hidden" value="<?php echo $phonebook['id']; ?>" />
                        <input name='user_id' hidden type="hidden" value="<?php echo $user['id']; ?>" />
                    </form>
                    <div class="share<?php echo $phonebook['id']; ?>" name="<?php echo $phonebook['id']; ?>" id="<?php echo $phonebook['id']; ?>">
                    
                        <button id="<?php echo 'makeprivate'.$phonebook['id']; ?>" value="1" pb_id="<?php echo $phonebook['id']; ?>" user_id="<?php echo $phonebook['user_id']; ?>" class="w3-button w3-green w3-left-align public w3-margin-bottom" style="<?php echo ($phonebook['public'] == "1" ? '' : 'display:none;'); ?>" onclick="makePrivate(this)">MAKE PRIVATE</button>

                        <button id="<?php echo 'makepublic'.$phonebook['id']; ?>" value="0" pb_id="<?php echo $phonebook['id']; ?>"  user_id="<?php echo $phonebook['user_id']; ?>" class="w3-button w3-yellow w3-left-align private w3-margin-bottom" style="<?php echo ($phonebook['public'] == "0" ? '' : 'display:none;'); ?>" onclick="makePublic(this)">MAKE PUBLIC</button>
                        
                        <input name='phonebook_id' hidden type="hidden" value="<?php echo $phonebook['id']; ?>" />
                        <input name='user_id' hidden type="hidden" value="<?php echo $user['id']; ?>" />
                    </div>
                    <form action="phonebooks/delete_phonebook.php" method="POST" class="w3-form"/>
                        <button name="delete" type="submit" value="<?php echo $phonebook['id']; ?> " class="w3-button w3-red w3-left-align">
                            DELETE
                        </button>
                        <input name='phonebook_id' hidden type="hidden" value="<?php echo $phonebook['id']; ?>" />
                        <input name='user_id' hidden type="hidden" value="<?php echo $user['id']; ?>" />
                    </form>
                </div>
                <hr>

                <?php
            }

            ?>
            <div class="w3-container w3-responsive w3-row w3-centre w3-margin-bottom">
                <h3>Create a new Phone Book</h3>
                <form action="/phonebook" method="POST">
                    @csrf
                    <div class="w3-row_ w3-responsive">
                        <div class="w3-block w3-col s12 m9 l6">
                            <input name='name' class="w3-input" type="text" required placeholder="Phonebook Name" />
                        </div>
                        <input name='user_id' hidden type="hidden" value="<?php echo $user['id'] ?>" />
                        <br/>
                        <div class="w3-block w3-col w3-third w3-col s12 m9 l7">
                            <textarea name='description' type="text" placeholder="note" class="w3-input"></textarea>
                        </div>
                        <br>
                        <div class="w3-col"><input class="w3-btn w3-green w3-center w3-margin-top" name="submit" type="submit" value="CREATE NEW PHONEBOOK"/></div>
                    </div>
                </form>
            </div>
            <br/>
            
            <script src="scripts/jquery.min.js"></script>
            <script>
                function changePublicity(action, phonebook_id, user_id) {
                    var phonebook_id = phonebook_id
                    $.post("phonebooks/share_phonebook.php",
                        {
                            phonebook_id: phonebook_id,
                            user_id: user_id,
                            action: action
                        },
                        function(data, status){					
                            data = JSON.parse(data);
                            messenger(data, phonebook_id);
                            console.log("stop1");
                        }
                    );
                    function messenger(data, phonebook_id) {
                        togglePrivacy(phonebook_id, data.data.is_public);                
                        alert(data.message);
                        console.log(phonebook_id);
                    }
                }
                function makePrivate(element){
                    var phonebook_id = element.getAttribute('pb_id');
                    var user_id = element.getAttribute('user_id');
                    var action = "unshare";
                    //console.log(element);
                    changePublicity(action, phonebook_id, user_id);
                }
                function makePublic(element){
                    var phonebook_id = element.getAttribute('pb_id');
                    var user_id = element.getAttribute('user_id');
                    var action = "share";
                    //console.log(element);
                    changePublicity(action, phonebook_id, user_id);
                }
                function toggle(id) {
                    var x = document.getElementById('makepublic'+id);
                    var y = document.getElementById('makeprivate'+id);
                    if (x.className.indexOf("w3-show") == -1) {
                        x.className += " w3-show";
                        y.className = y.className.replace(" w3-show", "w3-hide");
                    } else { 
                        x.className = x.className.replace(" w3-show", "w3-hide");
                        y.className += " w3-show";
                    }
                }
                function togglePrivacy(phonebook_id, value) {
                    var id = phonebook_id;
                    var x = document.getElementById('makepublic'+id);
                    var y = document.getElementById('makeprivate'+id);
                    //console.log('value is '+value);
                    //console.log(y);
                    var is_public;
                    if(value == "1" || value == 1) {
                        is_public = true;
                    } else {
                        is_public = false;
                    }
                    if (is_public == true) {
                        /*y.className = y.className.replace(" w3-hide", " w3-show");
                        if (y.className.indexOf("w3-show") == -1) {                    
                            y.className += " w3-show";
                            x.className = x.className.replace(" w3-show", "");
                            x.className.indexOf("w3-hide") == -1 ? x.className += 'w3-hide' : x.className += '';
                                                
                        }*/
                        y.style.display = 'block';
                        x.style.display = 'none';
                    } else if (is_public == false) { 
                        /*x.className = x.className.replace(" w3-hide", " w3-show");
                        if (x.className.indexOf("w3-show") == -1) {                    
                            x.className += " w3-show";
                            y.className = x.className.replace(" w3-show", "");
                            y.className.indexOf("w3-hide") == -1 ? x.className += 'w3-hide' : x.className += '';                    
                        }*/
                        x.style.display = 'block';
                        y.style.display = 'none';
                    }
                }
                
                $(document).ready(function () {
                    console.log('document');
                });
            </script>

        </div>
    </div>
</x-app-layout>
