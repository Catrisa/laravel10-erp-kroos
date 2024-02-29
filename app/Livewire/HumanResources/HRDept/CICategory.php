<?php
namespace App\Livewire\HumanResources\HRDept;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\HumanResources\ConditionalIncentiveCategory;

class CICategory extends Component
{
	// #[On('cicategorycreate')]
	protected $listeners = ['cicategorycreate' => 'render'];

	public function render()
	{
		return view('livewire.humanresources.hrdept.cicategory', [
			'cicategories' => ConditionalIncentiveCategory::all(),
		]);
	}

	public function del(ConditionalIncentiveCategory $cicategories)
	{
		$cicategories->delete();
	}

}
