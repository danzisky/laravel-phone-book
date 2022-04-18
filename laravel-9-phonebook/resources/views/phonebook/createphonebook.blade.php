<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create a New Phonebook') }}
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

            echo '<div class="w3-xxlarge w3-panel">Welcome '.$user['name'].'</div>';
            echo '<a href="'.route('phonebooks.index').'"><div class="w3-col"><input class="w3-button w3-light-grey w3-border-grey w3-center w3-margin-top" name="submit" type="submit" value="BACK TO PHONEBOOKS"/></div></a>';
            

            ?>
            <div class="w3-container w3-responsive w3-row w3-centre w3-margin-bottom">
                <h3>Create a new Phone Book</h3>
                <form action="{{ route('phonebooks.store') }}" method="POST">
                    @csrf
                    <div class="w3-row_ w3-responsive">
                        <div class="w3-block s12">
                            <input name='name' class="w3-input" type="text" required placeholder="Phonebook Name" />
                        </div>
                        <input name='user_id' hidden type="hidden" value="<?php echo $user['id'] ?>" />
                        <br/>
                        <div class="w3-block s12">
                            <textarea name='description' type="text" placeholder="note" class="w3-input"></textarea>
                        </div>
                        <br>
                        <div class="w3-col"><input class="w3-btn w3-green w3-center w3-margin-top" name="submit" type="submit" value="CREATE NEW PHONEBOOK"/></div>
                    </div>
                </form>
            </div>
            <br/>
        </div>
    </div>
</x-app-layout>
