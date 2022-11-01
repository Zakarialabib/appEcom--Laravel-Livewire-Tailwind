<?php

namespace App\Http\Livewire\Admin\Slider;

use Livewire\Component;
use App\Models\Slider;
use Livewire\WithPagination;
use App\Http\Livewire\WithSorting;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;
use Str;
use Illuminate\Support\Facades\Gate;

class Index extends Component
{
    use WithPagination, WithSorting,
        LivewireAlert, WithFileUploads;

    public $listeners = [
        'confirmDelete', 'delete','refreshIndex',
        'showModal','editModal'
    ];

    public $showModal;

    public $refreshIndex;

    public $editModal; 

    public int $perPage;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    public array $listsForFields = [];

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'id',
        ],
        'sortDirection' => [
            'except' => 'desc',
        ],
    ];

    public function getSelectedCountProperty()
    {
        return count($this->selected);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function resetSelected()
    {
        $this->selected = [];
    }

    public function refreshIndex()
    {
        $this->resetPage();
    }

    public array $rules = [
        'slider.title' => ['required', 'string', 'max:255'],
        'slider.subtitle' => ['nullable', 'string'],
        'slider.details' => ['nullable', 'string'],
        'slider.position' => ['nullable', 'string'],
        'slider.link' => ['nullable', 'string'],
        'slider.language_id' => ['nullable', 'string'],
        'slider.bg_color' => ['nullable', 'string'],
    ];

    public function mount()
    {
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 25;
        $this->paginationOptions = [25, 50, 100];
        $this->orderable         = (new Slider())->orderable;
    }

    public function render()
    {
        $query = Slider::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $sliders = $query->paginate($this->perPage);

        return view('livewire.admin.slider.index', compact('sliders'));
    }

    public function editModal(Slider $slider)
    {
        abort_if(Gate::denies('slider_edit'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->slider = $slider;

        $this->editModal = true;
    }
   
    public function update()
    {
        abort_if(Gate::denies('slider_edit'), 403);

        $this->validate();
        // upload image if it does or doesn't exist

        if($this->image){
            $imageName = Str::slug($this->slider->name).'.'.$this->image->extension();
            $this->image->storeAs('sliders',$imageName);
            $this->slider->image = $imageName;
        }

        $this->slider->save();

        $this->editModal = false;

        $this->alert('success', __('Slider updated successfully.'));
    }

    public function showModal(Slider $slider)
    {
        abort_if(Gate::denies('slider_show'), 403);

        $this->resetErrorBag();

        $this->resetValidation();

        $this->slider = $slider;

        $this->showModal = true;
    }

    public function delete(Slider $slider)
    {
        abort_if(Gate::denies('slider_delete'), 403);
       
        $slider->delete();

        $this->alert('success', __('Slider deleted successfully.'));

    }

}