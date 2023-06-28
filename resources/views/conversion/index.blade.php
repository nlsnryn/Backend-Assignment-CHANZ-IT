@extends('layouts.app')

@section('content')
<div class="text-white">
  <h1 class="text-5xl mb-10">Welcome to my Conversion App</h1>
  <div class="flex flex-row gap-4">
    <button id="number" class="w-fit bg-red-800 rounded-md px-6 py-2 text-xl">Convert number to word</button>
    <button id="words" class="w-fit bg-red-800 rounded-md px-6 py-2 text-xl">Convert word to number</button>
  </div>
</div>

<script>
    $(document).ready(function() {
        $('#number').click(function() {
            window.location.href = "{{ route('convert.number') }}";
        });
    });

    $(document).ready(function() {
        $('#words').click(function() {
            window.location.href = "{{ route('convert.words') }}";
        });
    });
</script>
@endsection