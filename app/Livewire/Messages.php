<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Message;
class Messages extends Component
{   
   
    public $message;
    public $allmessages;
    public $sender;
    // public $allmessages;
    public function render()
    {
        $users =User::all();
        $sender=$this->sender; 
        $this->allmessages;
        return view('livewire.messages',compact('users', 'sender'));
    }
    public function resetFrom(){
        $this->message="";
    }
    public function mountdata(){
        if (isset($this->sender->id)) {
            $user =User::find($this->sender->id);
            $this->sender=$user;
            $this->allmessages=Message::where('user_id',auth()->id())
            ->where('receiver_id',$this->sender->id)
            ->orWhere('user_id',$this->sender->id)
            ->where('receiver_id',auth()->id())
            ->orderBy('id','desc')->get();
            $not_seen = Message::where('user_id',$this->sender->id)
            ->where('receiver_id',auth()->id());
            $not_seen->update(['is_seen'=>true]);
            // $not_seen->save();

        }
    }
    public function SendMessage(){
      $data = new Message;
      $data->message=$this->message;
      $data->user_id=auth()->id();
      $data->receiver_id=$this->sender->id;
      $data->save();
      $this->resetFrom();
    }


    public function getUser($userId)
    {
        $user =User::find($userId);
        $this->sender=$user;
        $this->allmessages=Message::where('user_id',auth()->id())->where('receiver_id',$userId)->orWhere('user_id',$userId)->where('receiver_id',auth()->id())->orderBy('id','desc')->get();
    }
}
