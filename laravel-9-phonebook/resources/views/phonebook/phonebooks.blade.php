<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage your Phonebooks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w3-container">
            
            <?php
            $phonebooks = $phonebooks;

            echo '<div class="w3-xlarge w3-panel">Your phonebooks</div>';

            if(empty($phonebooks) || is_null($phonebooks)) {
                echo '<div class="w3-medium w3-panel w3-center">No Phone Book created yet</div>';
            }
            echo '<div class="w3-margin-bottom"><a href="'.route('phonebooks.create').'"><input class="w3-btn w3-green w3-center w3-margin-top" name="submit" type="submit" value="CREATE NEW PHONEBOOK"/></a></div>';

            foreach ($phonebooks as $phonebook) {
                ?>
                <div class=" w3-padding w3-row-padding w3-white w3-margin-bottom w3-leftbar w3-border-gray">
                    <div class="w3-col s12 m7 w3-white w3-row">
                        <a href="{{ route('phonebooks.contacts.index', ['phonebook' => $phonebook['id']]) }}" method="GET" class="w3-col s12"/>
                            @csrf
                            <button value="<?php echo $phonebook['id']; ?> " class="w3-button w3-col s12 w3-light-grey w3-left-align">
                                <div><h3><?php echo $phonebook['phonebook_name']; ?></h3></div>
                                <div><h5><?php echo $phonebook['phonebook_description']; ?></h5></div>
                                
                                
                            </button>
                            
                        </a>
                        <div class="w3-col s12 w3-small w3-margin w3-text-hover-blue w3-text-blue"><a href="<?php echo route('phonebook', ['id' => $phonebook['id']]); ?>" target="_blank">VIEW PHONEBOOK THROUGH LINK</a></div>
                    </div>
                    <div class="w3-row-padding w3-col s12 m5">
                        <form action="{{ route('phonebooks.edit', ['phonebook' => $phonebook['id']]) }}" method="GET" class="w3-col s4 m12"/>
                            <button type="submit" class="w3-button w3-grey w3-left-align w3-margin-bottom">EDIT</button>
                        </form>
                        <div class="share<?php echo $phonebook['id']; ?> w3-col s4 m12" name="<?php echo $phonebook['id']; ?>" id="<?php echo $phonebook['id']; ?>">
                            @csrf
                            <button id="<?php echo 'makeprivate'.$phonebook['id']; ?>" value="1" pb_id="<?php echo $phonebook['id']; ?>" user_id="<?php echo $phonebook['user_id']; ?>" class="w3-button w3-green w3-left-align public w3-margin-bottom" style="<?php echo ($phonebook['public'] == "1" ? '' : 'display:none;'); ?>" onclick="makePrivate(this)">MAKE PRIVATE</button>

                            <button id="<?php echo 'makepublic'.$phonebook['id']; ?>" value="0" pb_id="<?php echo $phonebook['id']; ?>"  user_id="<?php echo $phonebook['user_id']; ?>" class="w3-button w3-yellow w3-left-align private w3-margin-bottom" style="<?php echo ($phonebook['public'] == "0" ? '' : 'display:none;'); ?>" onclick="makePublic(this)">MAKE PUBLIC</button>
                            
                            <input name='phonebook_id' hidden type="hidden" value="<?php echo $phonebook['id']; ?>" />
                            <input name='user_id' hidden type="hidden" value="<?php echo $user['id']; ?>" />
                        </div>
                        <form action="{{ route('phonebooks.destroy', ['phonebook' => $phonebook['id']]) }}" method="POST" class="w3-form w3-col s4 m12"/>
                            @csrf
                            @method('delete')
                            <button name="delete" type="submit" value="<?php echo $phonebook['id']; ?> " class="w3-button w3-red w3-left-align">
                                DELETE
                            </button>
                            <input name='phonebook_id' hidden type="hidden" value="<?php echo $phonebook['id']; ?>" />
                            <input name='user_id' hidden type="hidden" value="<?php echo $user['id']; ?>" />
                        </form>
                    </div>
                </div>
                
                <hr>

                <?php
            }

            ?>
            
                
            </div>
            <br/>
            
            <script src="{{ asset('js/jquery.min.js') }}"></script>
            <script>
                function changePublicity(action, phonebook_id, user_id) {
                    var phonebook_id = phonebook_id;
                    var data = {
                            phonebook_id: phonebook_id,
                            user_id: user_id,
                            action: action,
                        }
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: "{{ route('publicity') }}",
                        data: data,
                        dataType: "json",
                        success: function (response) {
                            console.log(phonebook_id);
                            messenger(response, phonebook_id);
                        }
                    });
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
                    console.log(element);
                    changePublicity(action, phonebook_id, user_id);
                }
                function makePublic(element){
                    var phonebook_id = element.getAttribute('pb_id');
                    var user_id = element.getAttribute('user_id');
                    var action = "share";
                    console.log(element);
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
                        y.style.display = 'block';
                        x.style.display = 'none';
                    } else if (is_public == false) { 
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
