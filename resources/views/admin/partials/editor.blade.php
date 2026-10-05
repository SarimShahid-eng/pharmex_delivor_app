<script src="{{ asset('admin_assets') }}/libs/flatpickr/flatpickr.min.js"></script>
<script>
    $(function() {        
        $(".human_datepicker").flatpickr({
            altInput: !0,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d"
        })
    })
</script>