@extends('layouts.admin')
@section('content')
<style>
    #datatables_buttons_info h2{
        color: black !important;
    }
    #datatables_buttons_info{
        color: black !important;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Groups</li>
                </ol>
            </div>
            <h4 class="page-title">{{ $title }} </h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <h4 class="header-title m-t-0">{{ (@$is_edit) ? 'Edit' : 'Add New' }} Task</h4>
            <p class="text-muted font-14 m-b-20">
                Here you can {{ (@$is_edit) ? 'update' : 'create' }} Task.
            </p>

            <form action="{{ route('admin.task.save') }}" class="ajaxForm" method="post" enctype='multipart/form-data' novalidate>
                @csrf
                <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="workflow_id">Task workflow</label>
                                <select class="form-control" id="workflow_id">
                                    <option value="">Select</option>
                                    @foreach ($task_workflows as $task_workflow)
                                        <option @if (isset($task_data) && $task_data->hashid == $task_workflow->hashid) selected @endif
                                            value="{{ route('admin.task.fecthWorkflow', ['id' => $task_workflow->hashid]) }}">
                                            {{ $task_workflow->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="region_name">Employees<span class="text-danger">*</span></label>
                            <select class="form-control" name="employee_id" required="">
                                <option value="">select</option>
                                @foreach($employee_data as $employee_data)
                                <option {{ isset($employee_data) && @$employee_data->id == @$task_data->employee_id ? 'selected' : ''}} value="{{ $employee_data->hashid }}">{{ $employee_data->employee_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="region_name">Month<span class="text-danger">*</span></label>
                            <select class="form-control" name="day" required="">
                                <option value="">select</option>
                            <?php for($y=0; $y<=11; $y++){ $a = date('m-Y', mktime(0, 0, 0, date('m')+$y, 1, date('Y'))); ?>
                                   <option value="{{  date('d-m-Y', mktime(0, 0, 0, date('m')+$y, 1, date('Y'))) }}" {{ @$a == @$task_data->month ? 'selected' : ''}}>{{  date('F Y', mktime(0, 0, 0, date('m')+$y, 1, date('Y'))) }}</option>

                            <?php } ?>
                                <!-- <option {{ @$task_data->day == 'Monday' ? 'selected' : ''}} value="Monday">Monday</option>
                                <option {{ @$task_data->day == 'Tuesday' ? 'selected' : ''}} value="Tuesday">Tuesday</option>
                                <option {{ @$task_data->day == 'Wednesday' ? 'selected' : ''}} value="Wednesday">Wednesday</option>
                                <option {{ @$task_data->day == 'Thursday' ? 'selected' : ''}} value="Thursday">Thursday</option>
                                <option {{ @$task_data->day == 'Friday' ? 'selected' : ''}} value="Friday">Friday</option>
                                <option {{ @$task_data->day == 'Saturday' ? 'selected' : ''}} value="Saturday">Saturday</option> -->
                            </select>
                        </div>
                    </div>
                   <!--  <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="region_name">Groups<span class="text-danger">*</span></label>
                            <select class="form-control" name="group_id" required="">
                                <option value="">Select</option>
                                @foreach($group_data as $group_data)
                                <option {{ isset($group_data) && @$group_data->id == @$task_data->groups_id ? 'selected' : ''}} value="{{ $group_data->hashid }}">{{ $group_data->group_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> -->
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="area_name">Routs</label><span class="text-danger">*</span>
                            <select class="form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose ..." required value="{{ isset($region) ? $region->area_name : '' }}" name='region[]' >
                              @foreach($region_data as $region_data)
                                <option <?php if(isset($task_data)){ foreach (@$task_data->regions as $value) {
                                    if ($value->id == $region_data->id) {
                                        echo "selected";
                                    }
                                }} ?> value="{{ $region_data->hashid }}">{{ $region_data->region_name }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                   <!--  <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="area_name">Town Target</label><span class="text-danger">*</span>
                            <input type="text" class="form-control" name="target" value="{{ @$task_data->target }}" required="">
                        </div>
                    </div> -->
                </div>    
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group mb-3">
                            <label for="region_name">Company</label>
                            <select class="form-control" id="company_id">
                                <option value="">Select</option>
                                @foreach($company_data as $company_data)
                                <option value="{{ $company_data->id }}" data-name='{{ $company_data->company_name }}'>{{ $company_data->company_name }}</option>
                                @endforeach      
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="form-group mb-3">
                            <label for="region_name">Target</label>
                            <input type="Number" class="form-control" id="target_id" >
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group mb-3">
                                <button name="remove" type="button" class="btn btn-success" id="add" style="margin-top:27px"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
               
               <table class="table" id="sample_6">
                    <tr style="background-color: #686868; color: #FFF;">
                                                    <th>Company Name</th>
                                                    <th >Target</th>
                                                    <th width="2%">Action</th>
                                                   </tr>
 <tbody id="addrow">
                                    
  @if (isset($fetchWorkflow) && $task_data->company_task->count())
                        @foreach ($task_data->company_task as $companyTask)
                            @include('admin.partials.task_partial', [
                                'company_id' => $companyTask->company_id,
                                'name' => $companyTask->company->company_name ?? '',
                                'target' => $companyTask->target_amt,
                            ])
                        @endforeach
                    @endif
</tbody>
                                                <tr>
                                                    
                                                        <td>
                                                    <span style="font-size: 18px; padding-left: 77%; "><b>Total Target</b></span>
                                                </td>
                                                    <td colspan="2">
                                                        
                                                        <input type="text" readonly="" value="{{ @$task_data->target }}"  placeholder="0" name="total_amount" id="total_net" class="form-control" />
                                                    </td>
                                                </tr>

                </table> 
                <div class="form-group mb-3 text-right">
                    <input type="hidden" value="{{ @$task_data->hashid }}" name="task_id" />
                    <button class="btn btn-primary waves-effect waves-light" type="submit">
                        Submit
                    </button>
                    <button type="reset" class="btn btn-secondary waves-effect m-l-5">
                        Cancel
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js
"></script>
<script>
        document.getElementById('workflow_id').addEventListener('change', function() {
            var url = this.value; // get the selected option's value (route)
            if (url) {
                window.location.href = url; // redirect
            }
        });
    </script>
<script>
    $(document).ready(function() {
        var i=0;
        $('#add').click(function() {
            name = $('#company_id').find(':selected').data('name');
            company_id = $('#company_id').val();
            target = $('#target_id').val(); 
            if($('#row'+company_id).length == 1){
                alert('This company already add!');
                return false;
            }
            $('#addrow').append('<tr class="dynamic_row" id="row'+company_id+'">\n\
                                    <td >\n\
                                        <input type="hidden" class="form-control chzn-select price" tabindex="2" id="'+company_id+'" name="target['+company_id+'][company_id]" value="'+company_id+'"><input class="form-control chzn-select price" tabindex="2" id="'+company_id+'" name="company_name['+company_id+'][product_id]" value="'+name+'" readonly>\n\
                                    </td>\n\
                                    <td><input id="target'+company_id+'" type="text" class="form-control" name="target['+company_id+'][target_amt]"\n\
                                        value="'+target+'" readonly/></td>\n\
                                    <td><button type="button" name="remove" id="'+company_id+'" class="btn btn-danger btn_remove">X</button></td>\n\
                                </tr>');
            i++;
            calamount();
        });
        function calamount() {
            total_net = $('#total_net').val();
            $('#total_net').val(Number(total_net)+Number(target));
        }
        $(".table").delegate(".btn_remove", "click", function(){
            var id = $(this).attr('id');
            target = $('#target'+id).val(); 
            total_net = $('#total_net').val();
            $('#total_net').val(Number(total_net)-Number(target));
            $("#row"+id).remove();
        });
    });
</script>
@endsection