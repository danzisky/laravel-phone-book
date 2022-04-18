<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add a New Contact') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if($errors->any())
                    @foreach($errors->all() as $error)
                        <div class="p-6 w3-red w3-text-white-800 border-b border-gray-200">
                            <div>{{ $error }}</div>
                        </div>
                    @endforeach
                @endif
                @if(isset($messages[0]['message']))
                    @foreach($messages as $message)
                        <div class="p-6 w3-amber w3-text-white-800 border-b border-gray-200">
                            <div>{{ $message['message'] }}</div>
                        </div>
                    @endforeach
                @endif
            </div>
            <?php           

            // $user = Auth::user();
            $phonebook = $phonebook;
            $phonebook_id = $phonebook['id'];
            $current_url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
            $location = 'phonebooks/shared/?phonebook_id=';
            $index = strpos($current_url, 'phonebook.php');
            $base_url = substr($current_url, 0, $index);
            $url = $base_url.$location.$phonebook_id;
            $link = 'http://'.$url;

            echo '<div class="w3-xxlarge w3-panel">Logged in as '.$user['name'].'</div>';
            echo '<div class="w3-xxlarge w3-panel">'.$phonebook['phonebook_name'].': Add Contacts</div>';

            echo '<div class="w3-large w3-panel"><div>To share this phonebook, use this link</div>';
            echo '<div class="w3-text-blue w3-link w3-medium">'.$link.'</div></div>'; 

            echo '<a href="'.route('phonebooks.contacts.index', ['phonebook' => $phonebook_id]).'"><button  class="w3-medium w3-button w3-gray w3-margin-top w3-margin-bottom">BACK TO PHONEBOOK</button></a>';
            echo '<br>';
            ?>
            <div  class="w3-margin-top w3-padding-bottom">
                <h3>Add New Contact</h3>
                <form action="{{ route('phonebooks.contacts.store', ['phonebook' => $phonebook['id']]) }}" method="POST">
                    @csrf
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
                    <input class="contact-info" name='user_id' hidden type="hidden" value="<?php echo $user['id'] ?>" />
                    <input class="contact-info" name='phonebook_id' hidden type="hidden" value="<?php echo $phonebook_id; ?>" />
                    <br/>
                    <input class="w3-input w3-center w3-margin-right w3-green contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name="submit" type="submit" value="Create Contact"/>
                </form>
            </div>
            <br/>

            
        </div>
    </div>
</x-app-layout>
