<div class="col-md-6 col-xl-3">
    <div class="widget-rounded-circle card-box">
        <div class="row">
            <div class="col-5">
                <div class="avatar-lg rounded-circle bg-soft-{{$color}} border-{{$color}} border">
                    <i class="{{$icon}} font-22 avatar-title text-{{$color}}"></i>
                </div>
            </div>
            <div class="col-7">
                <div class="text-right">
                    <h3 class="text-dark mt-1">{{ $currency ?? '' }}<span data-plugin="counterup">{{ $slot }}</span></h3>
                    <p class="text-muted mb-1 text-truncate">{{$title}}</p>
                </div>
            </div>
        </div> <!-- end row-->
    </div> <!-- end widget-rounded-circle-->
</div> <!-- end col-->