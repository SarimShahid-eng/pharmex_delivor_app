
        <h4 class="header-title m-t-0">Add New Target</h4>
        <form id="add_target">
                @csrf
             <input type="hidden" id="employee_id" value="{{ @$user_id }}">
        <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="region_name">Month<span class="text-danger">*</span></label>
                            <select class="form-control" id="month" name="month" required="">
                                <option value="">select</option>
                            <?php for($y=0; $y<=11; $y++){ $a = date('m-Y', mktime(0, 0, 0, date('m')+$y, 1, date('Y'))); ?>
                                   <option value="{{  date('d-m-Y', mktime(0, 0, 0, date('m')+$y, 1, date('Y'))) }}" {{ @$a == @$task_data->month ? 'selected' : ''}}>{{  date('F Y', mktime(0, 0, 0, date('m')+$y, 1, date('Y'))) }}</option>

                            <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label for="area_name">Target</label><span class="text-danger">*</span>
                            <input type="text" class="form-control" id="target" value="{{ @$task_data->target }}" required="">
                        </div>
                    </div>
                </div>    
                <div class="form-group mb-3 text-right">
                    <input type="hidden" value="{{ @$task_data->hashid }}" id="task_id" />
                    <button class="btn btn-primary waves-effect waves-light" type="submit">
                        Submit
                    </button>
                </div>
        </form>
<div class="row">
        <table class="table dt_table table-bordered w-100 nowrap responsive">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Target</th>
                    <th>Achieve Target</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php    foreach ($items as $value) { ?>
            <tr>
            <td>{{$value->month_name}}</td>
            <td>{{$value->target}}</td>
            <td>{{$value->achieve_target}}</td>
            <td><a type="button" name="edit" onclick="editTarget('{{ hashids_encode($value->user_id) }}','{{ $value->id }}','{{ $value->month }}','{{ $value->target }}')" class="btn btn-outline-primary btn-rounded waves-effect waves-light"><i class="fas fa-pencil-alt edit_target" data-emp="{{ $value->user_id }}" data-id="{{ $value->id }}" data-month="{{ $value->month }}" data-target="{{ $value->target }}"></i></a>&nbsp;&nbsp;
            </td>
            </tr>
            <?php } ?>

            </tbody></table> </div>