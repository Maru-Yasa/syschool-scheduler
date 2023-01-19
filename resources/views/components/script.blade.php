<script>
    toastr.options.closeButton = true;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="my-csrf-token"]').attr('content')
        }
    });

    $.extend(true, $.fn.dataTable.defaults, {
        language:{
            url:"{{ url('Indonesian.json') }}",
        }
    });
</script>