<tr class="dynamic_row" id="row{{ $company_id }}">
    <td>
        {{-- Hidden company_id --}}
        <input 
            type="hidden" 
            class="form-control chzn-select price"
            name="target[{{ $company_id }}][company_id]" 
            value="{{ $company_id }}"
        >

        {{-- Company name --}}
        <input 
            class="form-control chzn-select price"
            name="company_name[{{ $company_id }}][product_id]" 
            value="{{ $name }}" 
            readonly
        >
    </td>

    <td>
        {{-- Target Amount --}}
        <input 
            id="target{{ $company_id }}" 
            type="text" 
            class="form-control" 
            name="target[{{ $company_id }}][target_amt]"
            value="{{ $target }}" 
            readonly
        >
    </td>

    <td>
        {{-- Remove Button --}}
        <button 
            type="button" 
            class="btn btn-danger btn_remove"
            id="{{ $company_id }}"
        >
            X
        </button>
    </td>
</tr>
