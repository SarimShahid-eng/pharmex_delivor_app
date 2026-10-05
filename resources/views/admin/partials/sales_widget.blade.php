<div class="col-md-6 col-xl-3">
    <div class="card-box">
        <i class="fa fa-coins text-muted float-right"></i>
        <h4 class="mt-0 font-16">Today's {{ $title }}</h4>
        <h2 class="text-{{ $color ?? 'primary' }} my-3 text-center">{{currency_symbol()}} <span data-plugin="counterup">{{ $slot }}</span></h2>
        <p class="text-muted mb-0"><small>Total {{$title}}:</small> <span class="text-{{$color}}">{{ get_price($total_amount ?? 0) }} </span></p>
        <!-- <span class="float-right"><i class="fa fa-caret-up text-success mr-1"></i>10.25%</span> -->
    </div>
</div>