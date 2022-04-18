<?php
    use Illuminate\Support\Facades\Auth;
?>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
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
                <?php
                echo '<div class="w3-container">';
                $user_id = $user['id'];
                if(isset($user_id)) {

                    if (isset(Auth::user()->id)) {
                        echo '<div class="w3-xxlarge w3-panel">Logged in as '.$user['first_name'].'</div>';
                    } else {
                        echo '<div class="w3-xxlarge w3-panel">Viewing '.$user['first_name'].'\'s Phonebook</div>';
                    }
                    echo '<div class="w3-xxlarge w3-panel">'.$phonebook['phonebook_name'].'</div>';

                    echo '<a href="'.route('phonebooks.contacts.index', ['phonebook' => $phonebook['id']]).'"><button  class="w3-medium w3-button w3-gray w3-margin-top w3-margin-bottom">BACK TO CONTACTS</button></a>';

                    echo '<div class="w3-xxlarge w3-panel">Contact Details for '.$contact['first_name'].' '.$contact['last_name'].'</div>';

                    if (isset($_SESSION['user_id'])) {
                    echo '<a href="phonebook.php"><button  class="w3-medium w3-button w3-gray w3-margin-top w3-margin-bottom">BACK TO PHONEBOOK</button></a>';
                    }    
                    echo '<br>';
                    
                    if($contact['hidden'] == 1 || $contact['hidden'] == "1") {
                        echo '<div class="w3-medium w3-panel w3-text-yellow">This contact can be seen in public phonebook</div>';
                    } else {
                        echo '<div class="w3-medium w3-panel w3-text-green">This contact is hidded from others in phonebook</div>';
                    }

                    ?>
                    <div  class="w3-margin-top w3-padding-bottom">
                        <h3>Contact Details</h3>
                        <div class="w3-container">
                            <div class="w3-div w3-panel w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3">First name : <?php echo $contact['first_name']; ?></div>
                            <div class="w3-div w3-panel w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3">Last Name : <?php echo $contact['last_name']; ?></div>
                            <div class="w3-div w3-panel w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m">Email : <?php echo $contact['email']; ?></div>
                            <div class="w3-div w3-panel w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m" >Phone Number : <?php echo $contact['phone']; ?></div>
                            <div class="w3-div w3-panel w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" >Address 1: <?php echo $contact['address1']; ?></div>
                            <div class="w3-div w3-panel w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" >Address 2: <?php echo $contact['address2']; ?></div>
                            <div class="w3-div w3-panel w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s6 m4 l3" >City : <?php echo $contact['city']; ?></div>
                            <div class="w3-div w3-panel w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s6 m4 l3" >State : <?php echo $contact['state']; ?></div>
                            <div class="w3-div w3-panel w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s6 m3 l3" >Zip Code : <?php echo $contact['zipcode']; ?></div>
                            <div class="w3-div w3-panel w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" >Country : <?php echo $contact['country']; ?></div>
                            <div class="w3-div w3-panel w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3">Notes: <?php echo $contact['notes']; ?></div>
                            <div class="w3-div w3-panel w3-margin-right contact-info w3-col_ w3-twothird w3-responsive s12 m4 l3" >Group : <?php echo $contact['contact_group']; ?></div>
                        </div>
                    </div>
                    <br/>


                    
                    <?php
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</x-app-layout>

