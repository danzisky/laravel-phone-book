<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add a New Contact') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
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
                <form action="{{ route('contacts.update', ['contact' => $contact['id']]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name='first_name' type="text" placeholder="First Name" value="{{ $contact['first_name'] }}" />
                    <input class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name='last_name' type="text" placeholder="Last Name" value="{{ $contact['last_name'] }}" />
                    <input class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name='email' type="email" placeholder="email" value="{{ $contact['email'] }}" />
                    <input class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name='phone' type="text" placeholder="Mobile Number" value="{{ $contact['phone'] }}" />
                    <textarea class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name='address1' type="text" placeholder="Adress 1" >{{ $contact['address1'] }}</textarea>
                    <textarea class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name='address2' type="text" placeholder="Adress 2" >{{ $contact['address2'] }}</textarea>
                    <input class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s6 m3 l3" name='city' type="text" placeholder="City" value="{{ $contact['city'] }}" />
                    <input class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s6 m3 l3" name='state' type="text" placeholder="State" value="{{ $contact['state'] }}" />
                    <input class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s6 m3 l3" name='zipcode' type="text" placeholder="Zip Code" value="{{ $contact['zipcode'] }}" />
                    <input class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name='country' type="text" placeholder="Country" value="{{ $contact['country'] }}" />
                    <textarea class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name='notes' type="text" placeholder="Notes">{{ $contact['notes'] }}</textarea>
                    <input class="w3-input w3-block w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name='Contact Group' type="text" placeholder="contact_group" value="{{ $contact['contact_group'] }}" />
                    <input class="w3-input w3-center w3-margin-right w3-green contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" name="submit" type="submit" value="Update Contact"/>
                </form>
            </div>
            <br/>

            
        </div>
    </div>
</x-app-layout>
