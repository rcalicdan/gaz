<?php

namespace App\Livewire\Dashboard;

use App\Models\Client;
use App\Models\Reminder;
use Livewire\Component;
use Livewire\WithPagination;

class RemindersWidget extends Component
{
    use WithPagination;

    public $activeTab = 'scheduled';
    public $daysThreshold = 30;
    public $showModal = false;
    public $modalClientId = null;
    public $modalNote = '';
    public $modalDate = '';

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function openReminderModal($clientId)
    {
        $this->modalClientId = $clientId;
        $this->modalDate = now()->addDay()->format('Y-m-d');
        $this->modalNote = '';
        $this->showModal = true;
    }

    public function saveReminder()
    {
        $this->validate([
            'modalClientId' => 'required|exists:clients,id',
            'modalNote' => 'required|string|max:500',
            'modalDate' => 'required|date|after_or_equal:today',
        ]);

        Reminder::create([
            'client_id' => $this->modalClientId,
            'user_id' => auth()->id(),
            'note' => $this->modalNote,
            'reminder_date' => $this->modalDate,
            'status' => 'pending'
        ]);

        $this->showModal = false;
        $this->dispatch('show-message', ['type' => 'success', 'message' => __('Reminder set successfully!')]);
    }

    public function completeReminder($reminderId)
    {
        $reminder = Reminder::find($reminderId);

        $reminder->update(['status' => 'completed']);

        $this->dispatch('show-message', ['type' => 'success', 'message' => __('Reminder completed!')]);
    }

    public function render()
    {
        $rows = [];

        if ($this->activeTab === 'scheduled') {
            $rows = Reminder::with('client')
                ->where('status', 'pending')
                ->where('reminder_date', '<=', now())
                ->orderBy('reminder_date', 'asc')
                ->paginate(5);
        } else {
            $rows = Client::needsContact($this->daysThreshold)
                ->orderBy('last_contact_date', 'asc')
                ->paginate(5);
        }

        return view('livewire.dashboard.reminders-widget', [
            'rows' => $rows
        ]);
    }
}
