@extends('layouts.app2')
@section('header')
    <div name="header">
        @if($access == true)
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user['name'] }}'s Shared Contact
        </h2>
        @endif
        @if($access != true)
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Contact could not be retrieved
        </h2>
        @endif
    </div>
@endsection

@section('slot')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                
                <?php
                echo '<div class="w3-container">';
                isset($user['id']) ? $user_id = $user['id'] : '';
                if(isset($user_id)) {
                    
                    echo $access == true ? '<div class="w3-xxlarge w3-panel">'.$phonebook['phonebook_name'].'</div>' : '';

                    echo $access == true ? '<a href="'.route('phonebook', ['id' => $phonebook['id']]).'"><button  class="w3-medium w3-button w3-gray w3-margin-top w3-margin-bottom">BACK TO PHONEBOOK</button></a>' : '';

                    echo $access == true ? '<div class="w3-xlarge w3-panel">Contact Details for '.$contact['first_name'].' '.$contact['last_name'].'</div>' : '';
                    echo '<br>';

                    ?>
                    @if($access == true)
                    <div  class="w3-margin-top w3-padding-bottom">
                        <h3>Contact Details</h3>
                        <div class="w3-form"/>
                            <button onclick="alert('contact saved :)')" class="w3-button w3-grey w3-left-align w3-margin-bottom">SAVE CONTACT</button>
                        </div>
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
                    @endif
                    <br/>


                    
                    <?php
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
@endsection
