<?php

namespace App\Http\Livewire\Admin\FeaturedBanner;

use App\Models\FeaturedBanner;
use App\Models\Language;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;


class Create extends Component
{
    use LivewireAlert , WithFileUploads;

    public $createFeaturedBanner;
    
    public $image;

    public $listeners = ['createFeaturedBanner'];
    
    public array $listsForFields = [];

    public function mount(FeaturedBanner $featuredbanner)
    {
        $this->featuredbanner = $featuredbanner;
        $this->initListsForFields();
    }

    protected $rules = [
        'featuredbanner.title' => ['required', 'string', 'max:255'],
        'featuredbanner.details' => ['nullable', 'string'],
        'featuredbanner.link' => ['nullable', 'string'],
        'featuredbanner.product_id' => ['nullable', 'integer'],
        'featuredbanner.language_id' => ['nullable', 'integer'],
    ];

    public function render()
    {
        abort_if(Gate::denies('featuredbanner_create'), 403);

        return view('livewire.admin.featured-banner.create');
    }

    public function createFeaturedBanner()
    {
        $this->resetErrorBag();

        $this->resetValidation();

        $this->createFeaturedBanner = true;
    }

    public function create()
    {
        $this->validate();

        if($this->image){
            $imageName = Str::slug($this->featuredbanner->title).'.'.$this->image->extension();
            $this->image->storeAs('featuredbanners',$imageName);
            $this->featuredbanner->image = $imageName;
        }

        $this->featuredbanner->save();

        $this->alert('success', __('FeaturedBanner created successfully.'));
        
        $this->emit('refreshIndex');
        
        $this->createFeaturedBanner = false;
    }

    public function initListsForFields()
    {
        $this->listsForFields['languages'] = Language::pluck('name', 'id')->toArray();
        $this->listsForFields['products'] = Product::pluck('name','id')->toArray();
    }


}