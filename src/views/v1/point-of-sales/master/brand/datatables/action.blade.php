<div class="btn-group" role="group">
	<a href="#" class="btn btn-flat btn-xs btn-info _edit" data-id="{{ $id }}"
		{{ auth()->user()->canUpdateBrands() ? '' : 'disabled' }}
	>
		<i class="fa fa-edit"></i>
	</a>
	<a href="#" class="btn btn-flat btn-xs btn-danger _destroy" data-id="{{ $id }}"
		{{ auth()->user()->canDestroyBrands() ? '' : 'disabled' }}
	>
		<i class="fa fa-trash"></i>
	</a>
</div>