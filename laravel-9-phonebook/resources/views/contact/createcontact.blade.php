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
            
            echo '<div class="w3-xlarge w3-panel">'.$phonebook['phonebook_name'].': Add Contacts</div>';

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
