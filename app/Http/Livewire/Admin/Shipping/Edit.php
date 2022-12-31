<?php

namespace App\Http\Livewire\Admin\Shipping;

use Livewire\Component;
use App\Models\Shipping;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Edit extends Component
{
    use LivewireAlert;
    
    public $shipping;

    public $editModal = false;
    
    public $langauges;

    public $listeners = [
        'editModal'
    ];

    public array $rules = [
        'shipping.is_pickup' => ['nullable'],
        'shipping.title' => ['required', 'string', 'max:255'],
        'shipping.subtitle' => ['nullable', 'string'],
        'shipping.cost' => ['required', 'string'],
        // 'shipping.language_id' => ['required', 'integer'],
    ];

    public function mount(Shipping $shipping)
    {
        $this->shipping = $shipping;

    }

    public function render()
    {
        return view('livewire.admin.shipping.edit');
    }

    public function editModal(Shipping $shipping)
    {
        // abort_if(Gate::denies('shipping_update'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->shipping = $shipping;

        $this->editModal = true;
    }

    public function update()
    {
        // abort_if(Gate::denies('shipping_update'), 403);

        $this->validate();

        $this->shipping->save();

        $this->editModal = false;

        $this->emit('refreshIndex');

        $this->alert('success', __('shipping updated successfully'));
    }
}
