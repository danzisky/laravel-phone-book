<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create a New Phonebook') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <?php
            
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
