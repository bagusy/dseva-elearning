@if (! empty(session('message')))
    <script>
        toastr.success('{!! session("message") !!}');
    </script>
@endif

@if (session()->has('status'))
    <script>
        toastr.info('{!! session()->get("status") !!}');
    </script>
@endif

@if (count($errors) > 0)
    @php
        $pesan = '';
        foreach ($errors->all() as $error){
            $pesan.=$error.', ';
        }
    @endphp

    <script>
        toastr.error('{{ $pesan }}');
    </script>
@endif
