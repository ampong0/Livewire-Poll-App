<?php

namespace App\Livewire;
use App\Models\Poll;
use Livewire\Component;

class CreatePoll extends Component
{
    public $title; // Property to store the title of the poll

    public $options = ['First']; // Property to store the poll options

    protected $rules = [
        'title'=> 'required|min:3|max:255',
         'options'=> 'required|array|min:1|max:10',
         'options.*' => 'required|min:1|max:255'
    ];

    protected $messages = [
        'options.*' => 'This option can\'t be empty.'
    ];

    public function render()
    {
        return view('livewire.create-poll'); // Render the Livewire component view
    }

    public function addOption()
    {
        $this->options[] = ''; // Add an empty option to the list of options
    }

    public function removeOption($index)
    {
        unset($this->options[$index]); // Remove an option at a specific index
        $this->options = array_values($this->options); // Re-index the options array
    }

    public function createPoll()
    {
        // Validate that the title is not empty
        $this->validate();
         

        // Create a new Poll record in the database with the provided title
        $poll = Poll::create([
            'title' => $this->title
        ])->options()->createMany(
            collect($this->options)->map(fn($option)=> ['name'=> $option])->all()

        );

        // Iterate through the options and create associated records in the database
        // foreach ($this->options as $optionName){
        //     $poll->options()->create(['name' => $optionName]);
        // }

        $this->reset(['title' , 'options']);
        $this->dispatch('pollCreated');
    }
}
